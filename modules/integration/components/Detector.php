<?php
namespace app\modules\integration\components;

use yii\base\Component;
use Yii;

/**
 * Integration possibility detector
 */
class Detector extends Component
{

    /**
     * Analyze URL and return integration module detected or false if no possibility to integrate resource
     *
     * @param $url
     *
     * @return string|boolean
     */
    public static function detect($url)
    {
        foreach (Yii::$app->getModule('integration')->modules as $id => $config) {
            if (method_exists(Yii::$app->getModule('integration')->getModule($id), 'getDetector')) {

                /** @var \app\modules\integration\components\IDetector $detector */
                $detector = Yii::$app->getModule('integration')->getModule($id)->getDetector();

                if ($detector->detect($url)) {
                    return $detector->getWelcomeMessage();
                }
            }
        }

        return false;
    }
}