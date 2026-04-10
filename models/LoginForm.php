<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\services\ConexaService;
use app\dtos\AuthDTO;
use app\dtos\ErrorResponseDTO;

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
        $ConexaService = new ConexaService();

        $authDTO = $ConexaService->login([
            'username' => $this->username,
            'password' => $this->password
        ]);
            
        if ($authDTO instanceof AuthDTO && $authDTO->statusCode == 200) {

            $user = User::findOne($authDTO->user->id);
            if (!$user) {
                $user = new User();
                $user->id = $authDTO->user->id;
            }

            $user->type = $authDTO->user->type;
            $user->name = $authDTO->user->name;
            $user->access_token = $authDTO->accessToken;
            $user->save();

            return Yii::$app->user->login($user,  $authDTO->expiresIn);
        }

        return false;
    }

}
