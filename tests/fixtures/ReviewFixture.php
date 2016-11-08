<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ReviewFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Review';
    public $depends = [
        'app\tests\fixtures\OrderFixture',
    ];
}
