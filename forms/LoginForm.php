<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\forms\admin\HelperForm;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            $user = User::findOne(['username' => $this->username]);
            if ($user && $user->validatePassword($this->password)) {
                return Yii::$app->user->login($user);
            }
        }
        return false;
    }

}
