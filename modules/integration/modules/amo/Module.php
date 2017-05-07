<?php

namespace app\modules\integration\modules\amo;

use app\modules\integration\modules\amo\components\Performer;
use Yii;
use app\modules\script\models\Call;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * @param Call $call
     */
    public function onCallEnd(Call $call)
    {
        (new Performer())->perform($call);
    }


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