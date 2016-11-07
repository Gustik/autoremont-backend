<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $visited_at
 * @property string $login
 * @property string $password_hash
 * @property string $access_token
 * @property int $auth_key
 * @property int $sms_code
 * @property int $sms_code_time
 * @property int $is_active
 * @property int $is_admin
 * @property bool $can_work
 * @property float $rating
 * @property Profile $profile
 * @property Order[] $orders
 * @property Review[] $reviews
 * @property Review[] $myReviews
 */
class User extends Model implements IdentityInterface
{
    public $authKey;
    //public $rating;
    const TEST_LOGIN = '+71234567890';
    const TEST_CODE = '1234';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function getAttributes($names = null, $except = [])
    {
        $values = parent::getAttributes($names, $except);
        $values['rating'] = $this->rating;

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['admin-create'] = ['login', 'is_admin', 'password'];
        $scenarios['admin-update'] = ['login', 'is_admin', 'password'];
        $scenarios['api-view'] = ['login', 'profile', 'rating'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            [['created_at', 'updated_at', 'banned_to', 'visited_at', 'password'], 'safe'],
            [['sms_code', 'sms_code_time', 'is_active', 'is_admin', 'can_work'], 'integer'],
            [['login', 'password_hash', 'access_token', 'password'], 'string', 'max' => 255],
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
            'can_work' => 'Может работать',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
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
     * @return bool On-Line status
     */
    public function getIsOnline()
    {
        return ((strtotime($this->visited_at)) > time() - 60 * 15) && $this->is_active;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * @return bool
     */
    public function validateSmsCode($code)
    {
        return $this->sms_code == $code;
    }

    /**
     * Generates a password hash.
     *
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
     * Написанные мне отзывы.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['mech_id' => 'id']);
    }

    /**
     * Написанные мной отзывы.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMyReviews()
    {
        return $this->hasMany(Review::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptedOrders()
    {
        return $this->hasMany(Order::className(), ['executor_id' => 'id'])->with('author');
    }

    /**
     * @return float|int
     */
    public function getRating()
    {
        $ratingSum = 0;
        foreach ($this->reviews as $review) {
            $ratingSum += $review->rating;
        }

        return $ratingSum > 0 ? $ratingSum / count($this->reviews) : 0;
    }

    /**
     * Return true, if user can accept order.
     *
     * @return bool
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
     * Return true, if user can decline order.
     *
     * @return bool
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
     * {@inheritdoc}
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
