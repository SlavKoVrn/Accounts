<?php

namespace api\models;

use common\models\Token;
use common\models\User;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * @return array|null
     */
    public function auth()
    {
        if ($this->validate() and $user = $this->getUser()) {
            $this->generateToken(time() + 3600 * 24);
            return [
                'token' => $user->rest_token,
                'expired' => date(DATE_RFC3339, strtotime($user->rest_token_expired_at)),
            ];
        } else {
            return null;
        }
    }

    public function generateToken($expire)
    {
        $user = $this->getUser();
        $user->rest_token = \Yii::$app->security->generateRandomString();
        $user->rest_token_expired_at = date('Y-m-d H:i:s', $expire);
        $user->save();
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
