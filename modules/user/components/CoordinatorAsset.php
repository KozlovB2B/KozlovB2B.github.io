<?php
namespace app\modules\user\components;

use yii\web\AssetBundle;

/**
 * Class OperatorLayoutAsset
 *
 * @package app\modules\user\components
 */
class CoordinatorAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/user/assets/coordinator';

    public $js = [
        'wsreconnectable.js',
        'ws-connection.js',
        'event.js',
        'coordinator.js'
    ];

    public function init()
    {
        parent::init();

        foreach ($this->js as $k => $v) {
            $this->js[$k] .= '?v=1';
        }
    }

    public $depends = [
        'app\modules\core\components\assets\JsToolsAsset',
        'app\modules\core\components\assets\YiijAsset',
    ];
}