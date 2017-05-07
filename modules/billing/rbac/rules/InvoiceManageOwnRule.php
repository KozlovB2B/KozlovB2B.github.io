<?php

namespace app\modules\billing\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * Allow for head manager update only own call end reason
 *
 * Class ScriptUpdateOwnRule
 * @package app\modules\site\rbac
 */
class InvoiceManageOwnRule extends Rule
{
    public $name = 'invoiceManageOwn';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            if ($params["invoice"]->account_id == $user) {
                return true;
            }
        }

        return false;
    }
}