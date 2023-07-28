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
class SignUpForm extends Model
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

    public function signup()
    {
        if($this->validate())
        {
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);

            // Yii::warning()
            if($user->save()){
                return true;
            }
            return false;
        }
    }

}
