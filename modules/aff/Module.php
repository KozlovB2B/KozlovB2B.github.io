<?php

namespace app\modules\aff;

use Yii;

class Module extends \yii\base\Module
{
//    public function init()
//    {
//        parent::init();
//    }
//
//    public $controllerNamespace = 'app\modules\users\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['aff'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/aff/messages',
            'fileMap' => [
                'aff' => 'aff.php'
            ],
        ];
    }
}