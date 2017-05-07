<?php

namespace app\modules\integration\modules\amo\components;


use app\modules\integration\components\IDetector;
use app\modules\integration\modules\amo\models\ApiCredentials;
use yii\base\Component;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * Class Detector
 *
 * Detects AMO integration possibility
 *
 * @package app\modules\integration\modules\amo\components
 */
class Detector extends Component implements IDetector
{

    /**
     * @inheritdoc
     */
    public function getWelcomeMessage()
    {
        return 'Получите уникальные возможности совместной работы ScriptDesigner и AmoCrm - ' . Html::a('подключить AmoCrm', Url::to(Url::to(['/integration/amo/api-credentials/index?pop-create-modal=1'])), ['target' => '_blank']);
    }

    /**
     * @inheritdoc
     */
    public function detect($url)
    {
        $regexp = '/^https:\/\/(.*)\.amocrm\.ru.*$/';

        preg_match($regexp, $url, $matches);

        if (isset($matches[1]) && !ApiCredentials::find()->where(['domain' => $matches[1], 'user_id' => Yii::$app->getUser()->getId()])->one()) {
            return true;
        }

        return false;
    }
}