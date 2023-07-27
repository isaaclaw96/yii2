<?php

namespace app\controllers;

use Yii;
use app\forms\SignUpForm;
use app\forms\WalletForm;
use app\models\User;
use app\models\Wallet;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;

class SignUpController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['index'];
        return $behaviors;
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            throw new BadRequestHttpException('User is already authenticated.');
        }

        $request = Yii::$app->request->getBodyParams();

        $model = new SignUpForm();
        $model->username = $request['username'];
        $model->password = $request['password'];
        if ($model->signup()) {
            // Signup successful, create a wallet for the user
            $user = User::findByUsername($model->username);
            $walletForm = new WalletForm();
            $wallet = $walletForm->createWalletForUser($user->id);

            return ['message' => 'User created successfully', 'user' => $user];
        }
        // how to display errors

        return ['error' => $model->errors];
    }

    // public function actionSuccess()
    // {
    //     return $this->render('success');
    // }

}
