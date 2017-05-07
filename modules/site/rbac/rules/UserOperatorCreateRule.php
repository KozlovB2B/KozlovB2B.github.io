<?php

namespace app\modules\site\rbac\rules;

use Yii;
use yii\rbac\Rule;
use \app\modules\billing\models\Account as BillingAccount;

/**
 * Allow for head manager update only own call end reason
 *
 * Class UserOperatorCreateRule
 * @package app\modules\site\rbac
 */
class UserOperatorCreateRule extends Rule
{
    public $name = 'userOperatorCreate';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            /** @var BillingAccount $billing */
            $billing = BillingAccount::findOne(Yii::$app->user->getId());
            return $billing->activeOperatorsCount() < $billing->operators_threshold;
        }

        return false;
    }
}
