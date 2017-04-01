<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class BillAccountFixture extends ActiveFixture
{
    public $modelClass = 'app\models\BillAccount';
    public $depends = [
        'app\tests\fixtures\UserFixture',
    ];
}
