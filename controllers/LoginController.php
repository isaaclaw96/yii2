<?php

namespace app\controllers;

use Yii;
use app\forms\LoginForm;
use app\forms\WalletForm;
use app\models\Wallet;
use yii\web\BadRequestHttpException;

class LoginController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // $behaviors['authenticator']['except'] = ['*'];
        return $behaviors;
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            throw new BadRequestHttpException('User is not authenticated');
        }

        $request = Yii::$app->request->getBodyParams();
        $model = new LoginForm();

        $model->username = $request['username'];
        $model->password = $request['password'];
        if ($model->login()) {
            // Check if a wallet exists for the user
            $user = Yii::$app->user->identity;
            $wallet = Wallet::findOne(['user_id' => $user->id]);

            if (!$wallet) {
                $walletForm = new WalletForm();
                $wallet = $walletForm->createWalletForUser($user->id);
            }
            return ['message' => 'Login successful', 'user' => $model, 'wallet' => $wallet];
        } else {
            $model->addError('password', 'Incorrect username or password.');
        }

        return ['error' => $model->errors];
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
