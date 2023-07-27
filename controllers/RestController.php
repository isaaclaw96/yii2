<?php

namespace app\controllers;

use app\forms\SignUpForm;
use app\models\User;
use app\models\Wallet;
use PDO;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class RestController extends ApiController
{

    public function behaviors()
    {
		$behaviors = parent::behaviors();
		$behaviors['authenticator']['except'] = ['*'];
		return $behaviors;
    }

    public function actionTest()
    {
        return 'Hello World';
    }

    public function actionCreateUser()
    {
        if (Yii::$app->user->isGuest) {
            throw new BadRequestHttpException('Authentication required.');
        }

        // User data from the request body
        $request = Yii::$app->getRequest();
        $username = $request->post('username');
        $password = $request->post('password');

        $model = new SignUpForm();
        $model->username = $username;
        $model->password = $password;

        if ($model->signup()) {
            // Signup successful, create a wallet for the user
            $user = User::findByUsername($model->username);
            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->amount = 0; // Set the initial balance to 0
            $wallet->save();

            return ['message' => 'User created successfully.', 'user' => $user];
        } else {
            // Failed to create user
            throw new ServerErrorHttpException('Failed to create user. Please check the provided data.');
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
