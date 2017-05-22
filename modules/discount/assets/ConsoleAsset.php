<?php
/**
 * @link http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\discount\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 *
 * @since 2.0
 */
class ConsoleAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/discount/web';
    public $css = [
        'css/bootstrap-pincode-input.css',
    ];
    public $js = [
        'js/bootstrap-pincode-input.js',
        'js/console.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
