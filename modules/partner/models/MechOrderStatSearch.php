<?php

namespace app\modules\partner\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MechOrderStat;

/**
 * MechOrderStatSearch represents the model behind the search form about `app\models\MechOrderStat`.
 */
class MechOrderStatSearch extends MechOrderStat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'name', 'birth_date', 'first_action', 'last_action'], 'safe'],
            [['orders_count', 'category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = MechOrderStat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'birth_date' => $this->birth_date,
            'orders_count' => $this->orders_count,
            'category_id' => $this->category_id,
            'first_action' => $this->first_action,
            'last_action' => $this->last_action,
            'city_id' => \Yii::$app->user->identity->profile->city_id,
        ]);

        $query->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
