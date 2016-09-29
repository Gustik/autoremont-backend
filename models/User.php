<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $visited_at
 * @property string $login
 * @property string $password_hash
 * @property string $access_token
 * @property integer $auth_key
 * @property integer $sms_code
 * @property integer $sms_code_time
 * @property integer $is_active
 * @property integer $is_admin
 *
 * @property Profile $profile
 */
class User extends Model implements IdentityInterface
{
    public $authKey;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['admin-create'] = ['login', 'is_admin', 'password'];
        $scenarios['admin-update'] = ['login', 'is_admin', 'password'];
        $scenarios['api-view'] = ['login', 'profile'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            [['created_at', 'updated_at', 'banned_to', 'visited_at', 'password'], 'safe'],
            [['sms_code', 'sms_code_time', 'is_active', 'is_admin'], 'integer'],
            [['login', 'password_hash', 'access_token', 'password'], 'string', 'max' => 255],
            [['login'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'visited_at' => 'Последний вход',
            'login' => 'Логин',
            'password' => 'Пароль',
            'password_hash' => 'Хэш пароля',
            'access_token' => 'Токен доступа',
            'sms_code' => 'Код доступа',
            'sms_code_time' => 'Время кода доступа',
            'name' => 'Имя',
            'isOnline' => 'Онлайн',
            'is_active' => 'Активен',
            'is_admin' => 'Администратор',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return User
     */
    public static function findIdentityByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @return Boolean On-Line status
     */
    public function getIsOnline()
    {
        return ( (strtotime($this->visited_at)) > time() -  60*15 ) && $this->is_active;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * @return Boolean
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * @return Boolean
     */
    public function validateSmsCode($code)
    {
        return $this->sms_code == $code;
    }

    /**
     * Generates a password hash
     * @param string $password user password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptedOrders()
    {
        return $this->hasMany(Order::className(), ['executor_id' => 'id'])->with('author');
    }

    /**
     * Return true, if user can accept order
     *
     * @return boolean
     **/
    public function canAcceptCall(Call $call, $type)
    {        
        if (array_search($type, ['client', 'mech']) === false) {
            return false;
        }
        if (($type == 'client' && $call->client_id == $this->id) ||
            ($type == 'mech' && $call->mech_id == $this->id)) {
                return true;
        }
        return false;
    }

    /**
     * Return true, if user can decline order
     *
     * @return boolean
     **/
    public function canDeclineCall(Call $call, $type)
    {        
        if (array_search($type, ['client', 'mech']) === false) {
            return false;
        }
        if (($type == 'client' && $call->client_id == $this->id) ||
            ($type == 'mech' && $call->mech_id == $this->id)) {
                return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->visited_at = date('Y-m-d H:i:s');
            return true;
        } else {
            return false;
        }
    }

    public function ban($date)
    {
        $this->banned_to = $date;
        return $this->save();
    }

    public function unban()
    {
        $this->banned_to = null;
        return $this->save();
    }
}