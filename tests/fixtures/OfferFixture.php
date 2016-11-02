<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class OfferFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Offer';
    public $depends = [
        'app\tests\fixtures\OrderFixture',
        'app\tests\fixtures\UserFixture',
    ];
}