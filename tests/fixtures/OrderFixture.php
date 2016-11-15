<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class OrderFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Order';
    public $depends = [
        'app\tests\fixtures\CategoryFixture',
        'app\tests\fixtures\UserFixture',
        'app\tests\fixtures\CityFixture',
    ];
}
