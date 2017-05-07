<?php

namespace app\modules\integration\modules\onlinepbx\components;


use app\modules\integration\components\IDetector;
use app\modules\integration\modules\onlinepbx\models\ApiCredentials;
use yii\base\Component;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * Class Detector
 *
 * Detects Online PBX integration possibility
 *
 * @package app\modules\integration\modules\onlinepbx\components
 */
class Detector extends Component implements IDetector
{

    /**
     * @inheritdoc
     */
    public function getWelcomeMessage()
    {
        return 'Получите уникальные возможности совместной работы ScriptDesigner и Online PBX - ' . Html::a('подключить Online PBX', Url::to(Url::to(['/integration/onlinepbx/api-credentials/index?pop-create-modal=1'])), ['target' => '_blank']);
    }

    /**
     * @inheritdoc
     */
    public function detect($url)
    {
        $regexp = '/^https:\/\/(.*)onlinepbx\.ru.*$/';

        $detected = preg_match($regexp, $url);

        if ($detected && !ApiCredentials::find()->where(['user_id' => Yii::$app->getUser()->getId()])->one()) {
            return true;
        }

        return false;
    }
}