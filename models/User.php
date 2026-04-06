<?php

namespace app\models;
use Yii;


class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $name;
    public $type;
    public $authKey;
    public $accessToken;

   

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = Yii::$app->session->get('user.id.' . $id);

        return empty($user) ? null : new static($user);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = Yii::$app->session->get('user.accessToken.' . $token);

        return empty($user) ? null : new static($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
