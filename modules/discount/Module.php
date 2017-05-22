<?php

namespace app\modules\discount;

use Yii;

/**
 * news module definition class.
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\discount\controllers';
    public $defaultRoute = 'main';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\discount\models\DiscountCompany',
            'enableAutoLogin' => false,
            'loginUrl' => ['discount/main/login'],
            'identityCookie' => ['name' => 'discount-company', 'httpOnly' => true],
            'idParam' => 'id', //this is important !
        ]);
        // custom initialization code goes here
    }
}
