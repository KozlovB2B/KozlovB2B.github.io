<?php

namespace app\modules\integration\modules\hookz;

use app\modules\integration\modules\hookz\components\HookEvent;
use app\modules\integration\modules\hookz\components\WebHookPerformer;
use app\modules\integration\modules\hookz\models\Hook;
use app\modules\script\models\Call;
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
     * @param Call $call
     */
    public function onCallStart(Call $call)
    {
        foreach (Hook::find()->byHead($call->account_id)->byEvent(HookEvent::ON_CALL_START)->all() as $hook) {
            WebHookPerformer::perform($call, $hook);
        }
    }

    /**
     * @param Call $call
     */
    public function onCallEnd(Call $call)
    {
        foreach (Hook::find()->byHead($call->account_id)->byEvent(HookEvent::ON_CALL_END)->all() as $hook) {
            WebHookPerformer::perform($call, $hook);
        }
    }

    /**
     * @param Call $call
     */
    public function onCallReport(Call $call)
    {
        foreach (Hook::find()->byHead($call->account_id)->byEvent(HookEvent::ON_REPORT)->all() as $hook) {
            WebHookPerformer::perform($call, $hook);
        }
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