<?php

namespace app\modules\script\rbac\rules;

use Yii;
use yii\rbac\Rule;
use app\modules\user\models\UserHeadManager;
use \app\modules\billing\models\Account as BillingAccount;

/**
 * Allow for head manager update only own call end reason
 *
 * Class CallEndReasonUpdateOwnRule
 * @package app\modules\site\rbac
 */
class CanExecuteScriptRule extends Rule
{
    public $name = 'canExecuteScript';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {

            $head_manager = UserHeadManager::findHeadManagerByUser();

            if(!$head_manager){
                return false;
            }

            if (!$head_manager->billing) {
                return false;
            }

            if ($head_manager->billing->executions_per_month > 0 && $head_manager->billing->executions_per_month < ($head_manager->executions_this_month + $head_manager->test_executions_this_month)) {
                return false;
            }

            if ($head_manager->billing->executions_per_day > 0 && $head_manager->billing->executions_per_day < ($head_manager->executions_today + $head_manager->test_executions_today)) {
                return false;
            }

            return true;
        }

        return false;
    }
}
