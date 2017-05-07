<?php
namespace app\modules\integration\components;

use yii\base\Component;
use Yii;
use app\modules\script\models\Call;

/**
 * Object that performs integration action if it possible for URL and current user
 */
class Performer extends Component
{

    /**
     * Perform integration action for given call object
     *
     * @param Call $call
     * @return bool
     */
    public static function perform(Call $call)
    {
        foreach (Yii::$app->getModule('integration')->modules as $id => $config) {
            if (method_exists(Yii::$app->getModule('integration')->getModule($id), 'getPerformer')) {
                /** @var \app\modules\integration\components\IPerformer $performer */
                $performer = Yii::$app->getModule('integration')->getModule($id)->getPerformer();
                $performer->perform($call);
            }
        }

        return false;
    }
}