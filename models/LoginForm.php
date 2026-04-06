<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\services\AuthService;

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


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $authService = new AuthService();

        $authDTO = $authService->login([
            'username' => $this->username,
            'password' => $this->password
        ]);
            
        if ($authDTO->statusCode == 200) {
            $params = [
                'id' => $authDTO->user->id,
                'type' => $authDTO->user->type,
                'name' => $authDTO->user->name,
                'authKey' => $authDTO->accessToken,
                'accessToken' => $authDTO->accessToken
            ];

            $user = new User($params);

            Yii::$app->session->set('user.id.' . $authDTO->user->id, $params);
            Yii::$app->session->set('user.accessToken.' . $authDTO->accessToken, $params);

            return Yii::$app->user->login($user,  $authDTO->expiresIn);
        }

        return false;
    }

}
