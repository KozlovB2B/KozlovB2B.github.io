<?php

namespace app\modules\billing\rbac\rules;

use Yii;
use yii\rbac\Rule;
use \app\modules\billing\models\Account as BillingAccount;

/**
 * Allow for head manager update only own call end reason
 *
 * Class CallEndReasonUpdateOwnRule
 * @package app\modules\site\rbac
 */
class BillingChangeRateRule extends Rule
{
    public $name = 'billingChangeRate';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            /** @var BillingAccount $account */
            $account = $params["account"];
            return $account->canChangeRateNow();
        }

        return false;
    }
}
