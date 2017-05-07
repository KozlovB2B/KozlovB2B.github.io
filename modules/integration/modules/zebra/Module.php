<?php

namespace app\modules\integration\modules\zebra;

use app\modules\script\models\Call;
use app\modules\integration\modules\zebra\components\Performer;
use Yii;

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