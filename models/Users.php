<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $salt
 * @property integer $role_id
 * @property integer $active
 * @property string $name
 * @property string $email
 * @property string $recovery_code
 * @property string $last_activity
 */

use app\modules\Settings\interfaces\UserRbacInterface;

class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface, UserRbacInterface
{

    public $password_repeat; // Поле повторения пароля.
    public $password_initial; // Поле исходного пароля.
    public $authKey;  // Поле исходного пароля.

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array(
            array(['login', 'name', 'email'], 'required', 'message' => 'Поле должно быть заполнено', 'on' =>['insert', 'update', 'updateWithPassword']),
            array(['password', 'password_repeat'], 'required', 'message' => 'Поле должно быть заполнено', 'on' => ['insert', 'updateWithPassword']),
            array(['login', 'name'], 'string', 'max' => 255, 'tooLong' => 'Максимальная длина не более {max} символов', 'on' => ['insert', 'update', 'updateWithPassword']),
            array(['login'], 'match', 'pattern'=>'/^[A-Za-z ]+$/', 'message'=>'Можно использовать только символы латинского алфавита', 'on' => ['insert', 'update', 'updateWithPassword']),
            array(['email'], 'email', 'message'=>'E-Mail не корректен', 'on' => ['insert', 'update', 'updateWithPassword']),
            array(['login'], 'unique', 'message' => '{attribute} "{value}" уже существует в базе', 'on' => ['insert', 'update', 'updateWithPassword']),

            array(['password', 'password_repeat'], 'string', 'min' => 3, 'tooShort' => 'Минимальная длина не менее {min} символов'),
           ['password_repeat', 'compare', 'skipOnEmpty'=> true, 'compareAttribute' => 'password', 'on' => ['insert', 'update', 'updateWithPassword']],
            array(['active'], 'number', 'integerOnly' => true, 'on' => ['insert', 'update', 'updateWithPassword']),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'login' => 'Логин',
            'name' => 'Имя',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль (повтор)',
            'role_id' => 'Роль',
            'active' => 'Активен',
            'type' => 'Тип',
            'office_id' => 'Офис',
        );
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            // Если пароль не равен старому, значит его поменяли и следует создать хеш.
            if (!empty($this->password) && $this->password != $this->password_initial)
            {
                // Обновляем хеш пароля.
                $this->salt = $this->_generateSalt();
                $this->password = $this->hashPassword($this->password);
            }else{
                $this->password = $this->password_initial;
            }

            return true;
        }
        return false;

    }


    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findByUsername($username)
    {

        $users = self::getAllUsers();
        foreach ($users as $user) {
            if (strcasecmp($user['login'], $username) === 0) {
                return new static($user); //вот здесь происходит ошибка

            }
        }
        return null;
    }
    private static function getAllUsers()
    {
        $everybody = Users::find()->all();
        return $everybody;
    }
    public function getId()
    {
        return $this->id;
    }

    public function hashPassword($password)
    {
        return md5($password . $this->salt);
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validatePassword($password){
        return $this->password === $this->hashPassword($password);
    }

    private function _generateSalt()
    {
        return substr(md5(time()), 5, 10);
    }

    public function validateAuthKey($password)
    {
        return $this->authKey === $password;
    }

    public static function findIdentityByAccessToken($token, $tape = null)
    {
        throw new yii\base\NotSupportedException;
    }

    public function getUserName(){
        return $this->name;
    }

}

