<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ProfileFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Profile';
    public $depends = [
        'app\tests\fixtures\UserFixture',
        'app\tests\fixtures\CityFixture',
    ];
}