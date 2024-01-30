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

    public static function createToken($user_id, $lifetimeAccess = (3600*24*2), $lifetimeRefresh = (3600*24*30)) {
        $token = new UsersToken();
        $token->user_agent = \Yii::$app->request->getUserAgent();
        $token->user_ip = \Yii::$app->request->getUserIP();
        $token->access_token = \Yii::$app->security->generateRandomString(120);
        $token->refresh_token = \Yii::$app->security->generateRandomString(120);
        $token->user_id = $user_id;
        $token->lifetimeAccess = $lifetimeAccess;
        $token->lifetimeRefresh = $lifetimeRefresh;
        $token->save();
        return UsersToken::findOne($token->id);
    }

}