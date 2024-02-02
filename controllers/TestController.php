<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;

class TestController extends \yii\rest\Controller
{

    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class,
            ]
        ];
    }

    public function actionIndex()
    {
        return 'TEST';
    }


}