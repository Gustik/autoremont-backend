<?php

namespace app\modules\admin\models;

use app\models\User;
use yii\base\Model;
use yii\helpers\Url;

class Stat extends Model
{
    public $label;
    public $value;
    public $link;

    public function __construct($label, $value = 0, $link = null)
    {
        parent::__construct();
        $this->label = $label;
        $this->value = $value;
        $this->link = $link;
    }

    public static function getRegistration()
    {
        $users = User::find()->all();
        $result = [
            'total' => new self('Всего', 0, Url::to(['/admin/user/index'])),
            'online' => new self('Онлайн', 0, Url::to(['/admin/user/index', 'is_online' => 1])),
            'active' => new self('Активных за месяц'),
            'new_day' => new self('Новых за день'),
            'new_week' => new self('Новых за неделю'),
            'new_month' => new self('Новых за месяц'),
        ];
        $now = time();
        foreach ($users as $user) {
            if ($user->is_active) {
                ++$result['total']->value;
                //Calculating activity
                if (($now - strtotime($user->visited_at)) < 60 * 60 * 24 * 30) {
                    ++$result['active']->value;
                    if (($now - strtotime($user->visited_at)) < 60 * 15) {
                        ++$result['online']->value;
                    }
                }
                //Calculating new
                if (($now - strtotime($user->created_at)) < 60 * 60 * 24 * 30) {
                    ++$result['new_month']->value;
                    if (($now - strtotime($user->created_at)) < 60 * 60 * 24 * 7) {
                        ++$result['new_week']->value;
                        if (($now - strtotime($user->created_at)) < 60 * 60 * 24) {
                            ++$result['new_day']->value;
                        }
                    }
                }
            }
        }
        $result['online']->value .= ' ('.round($result['online']->value / $result['total']->value * 100).'%)';
        $result['active']->value .= ' ('.round($result['active']->value / $result['total']->value * 100).'%)';

        return $result;
    }
}
