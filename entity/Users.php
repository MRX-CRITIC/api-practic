<?php

namespace app\entity;

use app\repository\UserRepository;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property int $is_admin
 *
 * @property Orders[] $orders
 * @property UserProfile[] $userProfiles
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['is_admin'], 'integer'],
            [['login'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 100],
            [['login'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'is_admin' => 'Is Admin',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserProfiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return UserRepository::getUserById($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return UserRepository::getUserByAccessToken($token);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        $userAgent = Yii::$app->request->getUserAgent();
        $userIP = Yii::$app->request->getUserIP();
        return UserRepository::getAccessToken($this->id, $userAgent, $userIP);
    }

    public function validateAuthKey($authKey)
    {
        return UserRepository::validateAccessToken($authKey);
    }

    public function createToken() {
        return UserRepository::createToken($this->id);
    }

    public function validatePassword($password) {
        return password_verify($password, $this->password);
    }

    public static function findByEmail($email) {
        return UserRepository::getUserByEmail($email);
    }

}
