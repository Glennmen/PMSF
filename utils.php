<?php

$localeData = null;

function i8ln($word)
{
    global $locale;
    if ($locale == "en") {
        return $word;
    }

    global $localeData;
    if ($localeData == null) {
        $filepath = 'static/dist/locales/' . $locale . '.min.json';
        if (file_exists($filepath)) {
            $json_contents = file_get_contents($filepath);
            $localeData = json_decode($json_contents, true);
        } else {
            return $word;
        }
    }

    if (isset($localeData[$word])) {
        return $localeData[$word];
    } else {
        return $word;
    }
}

function setSessionCsrfToken()
{
    if (empty($_SESSION['token'])) {
        generateToken();
    }
}

function refreshCsrfToken()
{
    global $sessionLifetime;
    if (time() - $_SESSION['c'] > $sessionLifetime) {
        session_regenerate_id(true);
        generateToken();
    }
    return $_SESSION['token'];
}

function generateToken()
{
    $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
    $_SESSION['c'] = time();
}

function validateToken($token)
{
    global $enableCsrf;
    if ((!$enableCsrf) || ($enableCsrf && isset($token) && $token === $_SESSION['token'])) {
        return true;
    } else {
        return false;
    }
}

function generateRandomString($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function createUserAccount($email, $password, $months)
{
    global $db;

    $new_expire_timestamp = time() + $months;
    $db->insert("users", [
        "email" => $email,
        "temp_password" => password_hash($password, PASSWORD_DEFAULT),
        "expire_timestamp" => $new_expire_timestamp
    ]);
    return true;
}

function resetUserPassword($email, $password, $resetType)
{
    global $db;

    if ($resetType == 0) {
        $db->update("users", [
            "temp_password" => password_hash($password, PASSWORD_DEFAULT)
        ], [
            "email" => $email
        ]);
    } elseif ($resetType == 1) {
        $db->update("users", [
            "password" => null,
            "temp_password" => password_hash($password, PASSWORD_DEFAULT)
        ], [
            "email" => $email
        ]);
    } else {
        $db->update("users", [
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "temp_password" => null
        ], [
            "email" => $email
        ]);
    }
    return true;
}

function updateExpireTimestamp($email, $new_expire_timestamp)
{
    global $db;

    $db->update("users", [
        "expire_timestamp" => $new_expire_timestamp
    ], [
        "email" => $email
    ]);
    return true;
}