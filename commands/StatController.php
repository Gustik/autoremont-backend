<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Stat;

class StatController extends Controller
{
    public function actionKeep($date = null)
    {
        return Stat::keep($date);
    }
}
