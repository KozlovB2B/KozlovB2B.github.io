<?php

namespace app\modules\site\controllers;

use app\modules\site\models\Tooltip;
use Yii;
use app\modules\core\components\CoreController;

class TooltipController extends CoreController
{

    /**
     * Skip some tooltip
     *
     * @param $tooltip_id
     */
    public function actionSkip($tooltip_id)
    {
        $this->checkAccess('site___tooltip__skip');
        Tooltip::skip($tooltip_id);
    }
}
