<?php

namespace app\repository;

use app\entity\Users;
use app\entity\UsersToken;

class UserRepository
{
    public static function getUserById($id)
    {
        return Users::find()
            ->where(['id' => $id])
            ->one();
    }

    public static function getUserByEmail($email)
    {
        return Users::find()
            ->where(['email' => $email])
            ->one();
    }

    public static function createToken($user_id, $lifetimeAccess = (3600 * 24 * 2), $lifetimeRefresh = (3600 * 24 * 30))
    {
        $token = new UsersToken();
        $token->user_agent = \Yii::$app->request->getUserAgent();
        $token->user_ip = \Yii::$app->request->getUserIP();
        $token->access_token = \Yii::$app->security->generateRandomString(120);
        $token->refresh_token = \Yii::$app->security->generateRandomString(120);
        $token->user_id = $user_id;
        $token->lifetime_access = $lifetimeAccess;
        $token->lifetime_refresh = $lifetimeRefresh;
        $token->save();
        return UsersToken::findOne($token->id);
    }

    public static function getAccessToken($userID, $userAgent, $userIP)
    {
        $token = UsersToken::find()
            ->where([
                'user_id' => $userID,
                'user_agent' => $userAgent,
                'user_ip' => $userIP,
            ])
            ->andWhere('NOW() <= (DATE_ADD(create_time, INTERVAL lifetime_access SECOND))')
            ->orderBy('create_time DESC')
            ->one();
        return $token->access_token;
    }

    public static function validateAccessToken($access_token)
    {
        $token = UsersToken::find()
            ->where([
                'access_token' => $access_token,
            ])
            ->andWhere('NOW() <= (DATE_ADD(create_time, INTERVAL lifetime_access SECOND))')
            ->one();
        return !empty($token);
    }

    public static function getUserByAccessToken($access_token)
    {
        $token = UsersToken::find()
            ->where([
                'access_token' => $access_token,
            ])
            ->andWhere('NOW() <= (DATE_ADD(create_time, INTERVAL lifetime_access SECOND))')
            ->one();
        if (!empty($token)) {
            return $token->user;
        }
        return null;
    }

    public static function getTokenByRefresh($refresh_token)
    {
        require UsersToken::find()
            ->where([
                'refresh_token' => $refresh_token,
            ])
            ->andWhere('NOW() <= (DATE_ADD(create_time, INTERVAL lifetime_access SECOND))')
            ->one();
    }

}
