<?php

namespace app\modules\integration;

use app\modules\core\components\BaseModule;
use Yii;

class Module extends BaseModule
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        $this->modules = [
            'recorder' => [
                'class' => 'app\modules\integration\modules\recorder\Module',
            ],
            'apiv2' => [
                'class' => 'app\modules\integration\modules\apiv2\Module',
            ],
            'widget' => [
                'class' => 'app\modules\integration\modules\widget\Module',
            ],
            'hookz' => [
                'class' => 'app\modules\integration\modules\hookz\Module',
            ],
            'amo' => [
                'class' => 'app\modules\integration\modules\amo\Module',
            ],
            'onlinepbx' => [
                'class' => 'app\modules\integration\modules\onlinepbx\Module',
            ],
            'zebra' => [
                'class' => 'app\modules\integration\modules\zebra\Module',
            ],
        ];
    }
}