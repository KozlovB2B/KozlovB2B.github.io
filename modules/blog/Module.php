<?php

namespace app\modules\blog;

use Yii;

class Module extends \yii\base\Module
{

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['blog'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/blog/messages',
            'fileMap' => [
                'aff' => 'aff.php'
            ],
        ];
    }
}