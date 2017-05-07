<?php

namespace app\modules\site\rbac\rules;

use Yii;
use yii\rbac\Rule;
use app\modules\user\models\UserHeadManager;

/**
 * Allow for head manager update only own operators
 *
 * Class UserOperatorUpdateChildrenRule
 * @package app\modules\site\rbac
 */
class VariantsReportRule extends Rule
{
    public $name = 'variantsReport';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            $model = UserHeadManager::findHeadManagerByUser();
            return $model->id == Yii::$app->user->getId() && $model->hits_report;
        }

        return false;
    }
}