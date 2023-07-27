<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // create view wallet permission
        $viewWallet =  $auth->createPermission('view-wallet');
        $viewWallet->description = 'View all users wallet';
        $auth->add($viewWallet);

        // create edit wallet permission
        $editWallet = $auth->createPermission('edit-wallet');
        $editWallet->description = 'Edit any wallet';
        $auth->add($editWallet);

        // create user role
        $user = $auth->createRole('user');
        $user->description = 'Normal User';
        $auth->add($user);

        // create admin role
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $viewWallet);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $editWallet);

        $auth->assign($admin, 1);
    }

}
