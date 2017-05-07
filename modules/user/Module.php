<?php

namespace app\modules\user;

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
        Yii::$app->i18n->translations['user'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/user/messages',
            'fileMap' => [
                'script' => 'user.php'
            ],
        ];
    }
}