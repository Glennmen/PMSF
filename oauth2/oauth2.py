#!/usr/bin/python3

import os
import sys
import asyncio
import json
import subprocess
import configargparse
from datetime import datetime
from flask import Flask, g, session, redirect, request, url_for, jsonify
from requests_oauthlib import OAuth2Session
from bot import Bot, get_path

def get_path(path):
    if not os.path.isabs(path):
        path = os.path.join(os.path.dirname(__file__), path)
    return path


def get_args():
    if '-cf' not in sys.argv and '--config' not in sys.argv:
        config_files = [get_path('config/config.ini')]
    parser = configargparse.ArgParser(default_config_files=config_files)
    parser.add_argument('-cf', '--config', is_config_file=True,
                        help='Configuration file')
    parser.add_argument('-ocid', '--OAUTH2_CLIENT_ID', type=str, required=True)						
    parser.add_argument('-ocs', '--OAUTH2_CLIENT_SECRET', type=str, required=True)
    parser.add_argument('-oru', '--OAUTH2_REDIRECT_URI', type=str, required=True)
    parser.add_argument('-token', '--bot_token', type=str, required=True)
    parser.add_argument('-role', '--verified_role', type=str, default='@everyone')
    parser.add_argument('-2fa', 'two-factor_auth', action='store_true', default=False)
    parser.add_argument('--workers', type=int)
    parser.add_argument('--bind', type=str)
    parser.add_argument('-m', type=str)
    parser.add_argument('wsgi:app', type=str)

    args = parser.parse_args()

    return args

args = get_args()

OAUTH2_CLIENT_ID = args.OAUTH2_CLIENT_ID
OAUTH2_CLIENT_SECRET = args.OAUTH2_CLIENT_SECRET
OAUTH2_REDIRECT_URI = args.OAUTH2_REDIRECT_URI
bot_token = args.bot_token

API_BASE_URL = os.environ.get('API_BASE_URL', 'https://discordapp.com/api')
AUTHORIZATION_BASE_URL = API_BASE_URL + '/oauth2/authorize'
TOKEN_URL = API_BASE_URL + '/oauth2/token'

app = Flask(__name__)
app.debug = True
app.config['SECRET_KEY'] = OAUTH2_CLIENT_SECRET

if 'http://' in OAUTH2_REDIRECT_URI:
    os.environ['OAUTHLIB_INSECURE_TRANSPORT'] = 'true'


def token_updater(token):
    session['oauth2_token'] = token


def make_session(token=None, state=None, scope=None):
    return OAuth2Session(
        client_id=OAUTH2_CLIENT_ID,
        token=token,
        state=state,
        scope=scope,
        redirect_uri=OAUTH2_REDIRECT_URI,
        auto_refresh_kwargs={
            'client_id': OAUTH2_CLIENT_ID,
            'client_secret': OAUTH2_CLIENT_SECRET,
        },
        auto_refresh_url=TOKEN_URL,
        token_updater=token_updater)


@app.route('/login')
def index():
        scope = request.args.get(
            'scope',
            'identify guilds')
        discord = make_session(scope=scope.split(' '))
        authorization_url, state = discord.authorization_url(AUTHORIZATION_BASE_URL)
        session['oauth2_state'] = state
        return redirect(authorization_url)


@app.route('/login/callback')
def callback():
    if request.values.get('error'):
        return request.values['error']
    discord = make_session(state=session.get('oauth2_state'))
    token = discord.fetch_token(
        TOKEN_URL,
        client_secret=OAUTH2_CLIENT_SECRET,
        authorization_response=request.url)
    session['oauth2_token'] = token
    return redirect(url_for('.me'))


@app.route('/login/me', methods=["GET"])
def me():
    discord = make_session(token=session.get('oauth2_token'))
    user = discord.get(API_BASE_URL + '/users/@me').json()
    guilds = discord.get(API_BASE_URL + '/users/@me/guilds').json()

    with open(get_path('server_info.json')) as server_file:
        data = json.load(server_file)

    try:
        if user['id'] not in data:
            data[user['id']] = {'username': user['username'],
	                        'recent_ip': request.headers["X-Forwarded-For"].split(',')[0]}
            if args.two-factor_auth is True:
                data[user['id']]['mfa_enabled'] = user['mfa_enabled']
            data['active'] = data[user['id']]
            data['active']['id'] = user['id']
            with open(get_path('server_info.json'), 'w') as server_file:
                json.dump(data, server_file, indent=4)
        else:
            data[user['id']]['last_login'] = str(datetime.today())
            data[user['id']]['recent_ip'] = request.headers["X-Forwarded-For"].split(',')[0]
            if args.two-factor_auth is True:
                data[user['id']]['mfa_enabled'] = user['mfa_enabled']
            data['active'] = data[user['id']]
            data['active']['id'] = user['id']
            with open(get_path('server_info.json'), 'w') as server_file:
                json.dump(data, server_file, indent=4)

        loop = asyncio.new_event_loop()
        client = Bot(loop=loop)
        asyncio.set_event_loop(loop)
        client.loop = loop
        loop.run_until_complete(client.login(bot_token))
        loop.run_until_complete(client.connect())

        with open(get_path('server_info.json')) as server_file:
            data = json.load(server_file)

        if 'verified' not in data[user['id']]:
            return "ACCESS DENIED"
        elif data[user['id']]['verified'] is False:
            return "INSUFFICIENT ROLE FOR ACCESS"
        elif args.two-factor_auth is True and data[user['id']]['mfa_enabled'] is False:
            return "PLEASE ENABLE 2-FACTOR AUTHORIZATION IN DISCORD"
        else:
            return redirect('/')
    except:
        with open(get_path('user_info.json'), 'w') as server_file:
            json.dump(user, server_file, indent=4)


if __name__ == '__main__':
    app.run('158.69.213.118')


