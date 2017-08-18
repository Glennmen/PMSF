#!/usr/bin/python3
# -*- coding: utf-8 -*-
 
import asyncio
import discord
import json
import os
from oauth2 import get_path, get_args
 
args = get_args()
 
class Bot(discord.Client):
 
    async def on_ready(self):
        await self.change_presence(status=discord.Status.invisible)
        for server in self.servers:
            for role in server.role_hierarchy:
                if role.name == args.verified_role:
                    verified_role = role
        with open(get_path('server_info.json')) as server_file:
            user_info = json.load(server_file)
        for member in self.get_all_members():
            if member.id == user_info['active']['id']:
                if member.top_role >= verified_role:
                    user_info[member.id]['verified'] = True
                    if 'authorized' not in user_info:
                        user_info['authorized'] = [user_info['active']['recent_ip']]
                    else:
                        user_info['authorized'].append(user_info['active']['recent_ip'])
                else:
                    user_info[member.id]['verified'] = False
        with open(get_path('server_info.json'), 'w') as server_file:
            json.dump(user_info, server_file, indent=4)
        await self.close()
