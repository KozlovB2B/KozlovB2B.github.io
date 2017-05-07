<?php

namespace app\modules\core\components;

use Yii;

class BaseModule extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations[$this->id] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/' . $this->id . '/messages',
            'fileMap' => [
                $this->id => $this->id . '.php'
            ],
        ];
    }

}