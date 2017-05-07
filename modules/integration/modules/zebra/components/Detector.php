<?php

namespace app\modules\integration\modules\zebra\components;

use app\modules\integration\components\IDetector;
use app\modules\integration\modules\zebra\models\ApiCredentials;
use yii\base\Component;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * Class Detector
 *
 * Detects Zebra integration possibility
 *
 * @package app\modules\integration\modules\zebra\components
 */
class Detector extends Component implements IDetector
{

    /**
     * @inheritdoc
     */
    public function getWelcomeMessage()
    {
        return 'Получите уникальные возможности совместной работы ScriptDesigner и Zebra - ' . Html::a('подключить Zebra', Url::to(Url::to(['/integration/zebra'])), ['target' => '_blank']);
    }

    /**
     * @inheritdoc
     */
    public function detect($url)
    {
        $regexp = '/^https:\/\/(.*)zebratelecom\.ru.*$/';

        $detected = preg_match($regexp, $url);

        if ($detected && !ApiCredentials::find()->where(['user_id' => Yii::$app->getUser()->getId()])->one()) {
            return true;
        }

        return false;
    }
}