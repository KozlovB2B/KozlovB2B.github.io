<?php

namespace app\modules\integration\controllers;

use app\modules\core\components\CoreController;
use app\modules\integration\components\Detector;
use Yii;

/**
 * DetectorController for integration possibility detect
 */
class DetectorController extends CoreController
{

    /**
     * Analyze URL and detect integration possibility
     *
     * @return string
     */
    public function actionDetect()
    {
        // Not checkAccess because for operators this method should return an empty string instead of error message
        if (Yii::$app->getUser()->can('integration___integration__manage')) {
            $url = Yii::$app->request->post('url', false);

            // Second IF for RBAC checking rule clean code
            if ($url) {
                if ($message = Detector::detect($url)) {
                    return $message;
                }
            }
        }

        return null;
    }
}
