<?php

namespace app\modules\script\rbac\rules;

use app\modules\user\models\UserHeadManager;
use Yii;
use yii\rbac\Rule;
use  \app\modules\billing\models\Account as BillingAccount;

/**
 * Allow for head manager update only own call end reason
 *
 * Class CallEndReasonUpdateOwnRule
 * @package app\modules\site\rbac
 */
class BillingScriptExportAllowedRule extends Rule
{
    public $name = 'billingScriptExportAllowed';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            /** @var BillingAccount $billing */
            $head_manager = UserHeadManager::findHeadManagerByUser();

            $billing = BillingAccount::findOne($head_manager->id);

            if (!!$billing->export_allowed && isset($params["script"])) {
                return $params["script"]->user_id == $head_manager->id;
            }else{
                return !!$billing->export_allowed;
            }
        }

        return false;
    }
}
