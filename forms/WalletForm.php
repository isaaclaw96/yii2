<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\forms\admin\HelperForm;
use app\models\Wallet;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * WalletForm is the model behind the wallet form.
 *
 * @property-read Wallet|null $user
 *
 */
class WalletForm extends Model
{
    public $amount;
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['amount', 'user_id'], 'required'],
            ['amount', 'double', 'min' => 0.01]

        ];
    }

    public function deposit()
    {
        if ($this->validate()) {
            return $this->updateWalletAmount('deposit');
        }
        return false;
    }

    public function withdraw()
    {
        if ($this->validate()) {
            return $this->updateWalletAmount('withdraw');
        }
        return false;
    }

    public function createWalletForUser($userId)
    {
        $wallet = new Wallet();
        $wallet->user_id = $userId;
        $wallet->amount = 0; // Set the initial balance to 0
        if ($wallet->save()) {
            return $wallet;
        } else {
            throw new NotFoundHttpException('Failed to create wallet for the user.');
        }
    }

    private function updateWalletAmount($action)
    {
        $wallet = Wallet::findOne(['user_id' => $this->user_id]);
        if (!$wallet) {
            throw new NotFoundHttpException('Wallet not found for the logged-in user.');
        }

        if ($action === 'withdraw' && $this->amount > $wallet->amount) {
            throw new UnprocessableEntityHttpException('Amount requested is larger than balance available.');
        }

        if ($action === 'deposit') {
            $wallet->amount += $this->amount;
        } elseif ($action === 'withdraw') {
            $wallet->amount -= $this->amount;
        }

        if ($wallet->save()) {
            return $wallet;
        }

        return false;
    }

}
