<?php

namespace app\modules\partner;

/**
 * news module definition class.
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\partner\controllers';
    public $defaultRoute = 'main';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
