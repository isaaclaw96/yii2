<?php

namespace app\controllers;

class UserController extends ApiController
{
    public $modelClass = 'app\models\User';

    
    public function actionIndex()
    {
        return 'Hello world';
    }

}
