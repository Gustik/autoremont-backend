<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    public $name;
    public $is_online;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sms_code', 'sms_code_time', 'is_active', 'is_admin', 'is_online'], 'integer'],
            [['name'], 'string'],
            [['created_at', 'updated_at', 'visited_at', 'login', 'password_hash', 'access_token'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return User::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()->joinWith('profile');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'name',
                    'login',
                    'is_active',
                    'is_admin',
                    'isOnline' => [
                        'asc' => ['user.visited_at' => SORT_ASC],
                        'desc' => ['user.visited_at' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user.id' => $this->id,
            'user.created_at' => $this->created_at,
            'user.updated_at' => $this->updated_at,
            'user.visited_at' => $this->visited_at,
            'user.sms_code' => $this->sms_code,
            'user.sms_code_time' => $this->sms_code_time,
            'user.is_active' => $this->is_active,
            'user.is_admin' => $this->is_admin,
        ]);

        $date = date('Y-m-d H:i:s', time() - 60 * 15);
        if ($this->is_online === '1') {
            $query->andFilterWhere(['>=', 'user.visited_at', $date]);
        } elseif ($this->is_online === '0') {
            $query->andFilterWhere(['<', 'user.visited_at', $date]);
        }

        $query->andFilterWhere(['like', 'user.login', $this->login])
            ->andFilterWhere(['like', 'profile.name', $this->name])
            ->andFilterWhere(['like', 'user.password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'user.access_token', $this->access_token]);

        return $dataProvider;
    }
}
