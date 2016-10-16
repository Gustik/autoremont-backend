<?php

namespace app\models;

use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "stat".
 *
 * @property integer $id
 * @property string $created_at
 * @property integer $user_total
 * @property integer $user_active
 * @property integer $user_new
 * @property integer $order_total
 * @property integer $order_new
 */
class Stat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['user_total', 'user_active', 'user_new', 'order_total', 'order_new'], 'required'],
            [['user_total', 'user_active', 'user_new', 'order_total', 'order_new'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Время',
            'user_total' => 'Всего (пользователи)',
            'user_active' => 'Активные (пользователи)',
            'user_new' => 'Новые (пользователи)',
            'order_total' => 'Всего (заказы)',
            'order_new' => 'Новые (заказы)',
        ];
    }

    public static function keep($date = null)
    {
        $date = ($date ?: date('Y-m-d H:i:s'));
        $prev = date ('Y-m-d H:i:s', strtotime('-1 day'.$date));
        $user = ['total' => 0, 'active' => 0, 'new' => 0];
        $order = ['total' => 0, 'new' => 0];

        $user['total'] = User::find()->count();
        $user['active'] = User::find()->where(['between', 'visited_at', $prev, $date])->count();
        $user['new'] = User::find()->where(['between', 'created_at', $prev, $date])->count();
        $order['total'] = Order::find()->count();
        $order['new'] = Order::find()->where(['between', 'created_at', $prev, $date])->count();

        $model = new Stat();
        $model->created_at = $date;
        $model->user_total = $user['total'];
        $model->user_active = $user['active'];
        $model->user_new = $user['new'];
        $model->order_total = $order['total'];
        $model->order_new = $order['new'];
        return $model->save();
    }

    public static function getGraphs($from, $to, $datasets = null)
    {
        $stat = Stat::find()->where(['between', 'created_at', $from, $to])->orderBy(['created_at' => SORT_ASC])->all();
        $graphs = ['labels' => [], 'datasets' => [
            'order_total' => [
                'label' => 'Всего (заказы)',
                'fillColor' => 'rgba(220,220,220,0.2)',
                'strokeColor' => 'rgba(220,220,220,1)',
                'pointColor' => 'rgba(220,220,220,1)',
                'pointStrokeColor' => '#fff',
                'data' => []
            ],
            'order_new' => [
                'label' => 'Новые (заказы)',
                'fillColor' => 'rgba(220,220,220,0.2)',
                'strokeColor' => 'rgba(220,220,220,1)',
                'pointColor' => 'rgba(220,220,220,1)',
                'pointStrokeColor' => '#fff',
                'data' => []
            ],
            'user_total' => [
                'label' => 'Всего (пользователи)',
                'fillColor' => 'rgba(151,187,205,0.2)',
                'strokeColor' => 'rgba(151,187,205,1)',
                'pointColor' => 'rgba(151,187,205,1)',
                'pointStrokeColor' => '#fff',
                'data' => []
            ],
            'user_active' => [
                'label' => 'Активные (пользователи)',
                'fillColor' => 'rgba(151,187,205,0.2)',
                'strokeColor' => 'rgba(151,187,205,1)',
                'pointColor' => 'rgba(151,187,205,1)',
                'pointStrokeColor' => '#fff',
                'data' => []
            ],
            'user_new' => [
                'label' => 'Новые (пользователи)',
                'fillColor' => 'rgba(151,187,205,0.2)',
                'strokeColor' => 'rgba(151,187,205,1)',
                'pointColor' => 'rgba(151,187,205,1)',
                'pointStrokeColor' => '#fff',
                'data' => []
            ]
        ]];
        $d = ceil(count($stat)/100);
        for ($i = 0; $i < count($stat); $i+=$d) { 
            $graphs['labels'][] = $stat[$i]->created_at;
            foreach ($datasets as $dataset) {
                if (!isset($graphs['datasets'][$dataset]) || !isset($stat[$i]->$dataset)) {
                    throw new BadRequestHttpException("Unknown dataset: {$dataset}");
                }
                $graphs['datasets'][$dataset]['data'][] = $stat[$i]->$dataset;
            }
        }
        return $graphs;
    }
}
