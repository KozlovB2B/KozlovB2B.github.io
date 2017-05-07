<?php

namespace app\modules\site;

use Yii;
use yii\base\Exception;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['site'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/site/messages',
            'fileMap' => [
                'script' => 'site.php'
            ],
        ];
    }
}