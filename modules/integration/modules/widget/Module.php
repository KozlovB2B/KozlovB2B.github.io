<?php

namespace app\modules\integration\modules\widget;

use Yii;

class Module extends \yii\base\Module
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    /**
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[$this->id] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/integration/modules/' . $this->id . '/messages',
            'fileMap' => [
                $this->id => $this->id . '.php'
            ],
        ];
    }
}