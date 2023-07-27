<?php

namespace app\controllers;

use app\forms\WalletForm;
use Yii;
use app\models\Wallet;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class WalletController extends ApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin', 'edit-wallet', 'view-wallet'],
                    'actions' => ['view-wallet']
                ],
                // 'except' => 
                // [
                //     'allow' => true,
                //     'roles' => ['@']
                // ]
            ]
        ];

        return $behaviors;
    }

    private function initWalletForm()
    {
        $userId = Yii::$app->user->id;
        $request = Yii::$app->request->getBodyParams();
        $model = new WalletForm();
        $model->user_id = $userId;
        $model->amount = $request['amount'];
        return $model;
    }

    public function actionDeposit()
    {
        $model = $this->initWalletForm();

        if ($wallet = $model->deposit()) {
            return ['message' => 'Deposit successful', 'wallet' => $wallet];
        }

        return ['error' => $model->errors];
    }

    public function actionWithdraw()
    {
        $model = $this->initWalletForm();

        if ($wallet = $model->withdraw()) {
            return ['message' => 'Withdraw successful', 'wallet' => $wallet];
        }

        return ['error' => $model->errors];
    }

    public function actionViewWallet()
    {
        return 'Hello Admin!';
    }

    // public function actionViewWallet()
    // {
    //     $userId = Yii::$app->user->id;
    //     $wallet = Wallet::findOne(['user_id' => $userId]);

    //     if (!$wallet) {
    //         throw new NotFoundHttpException('Wallet not found for the logged-in user.');
    //     }

    //     return $this->render('view-wallet', [
    //         'wallet' => $wallet,
    //         'editing' => false, // Set editing to false to display read-only details
    //     ]);
    // }

    // public function actionEditWallet()
    // {
    //     $userId = Yii::$app->user->id;
    //     $wallet = Wallet::findOne(['user_id' => $userId]);

    //     if (!$wallet) {
    //         throw new NotFoundHttpException('Wallet not found for the logged-in user.');
    //     }

    //     if (Yii::$app->request->isPost) {
    //         $newAmount = Yii::$app->request->post('Wallet')['amount'];
    //         if (is_numeric($newAmount) && $newAmount >= $wallet->amount) {
    //             // Update the wallet amount and save the changes
    //             $wallet->amount = $newAmount;
    //             $wallet->save();

    //             Yii::$app->session->setFlash('success', 'Wallet amount updated successfully.');
    //             return $this->redirect(['view-wallet']);
    //         } else {
    //             Yii::$app->session->setFlash('error', 'Invalid top-up amount. The new amount must be greater than or equal to the previous amount.');
    //         }
    //     }

    //     return $this->render('view-wallet', [
    //         'wallet' => $wallet,
    //         'editing' => true, // Set editing to true to display the form for editing
    //     ]);
    // }

    // public function actionAdminWallet()
    // {
    //     var_dump(Yii::$app->user);
    //     die;
    //     // Check if user has admin Privileges 
    //     if (Yii::$app->user->can('admin')) {
    //         echo 'You are an admin!';
    //     } else {
    //         Yii::$app->session->setFlash('error', "You do not have the rights to access this page!");
    //         return $this->goHome();
    //     }
    // }
}
