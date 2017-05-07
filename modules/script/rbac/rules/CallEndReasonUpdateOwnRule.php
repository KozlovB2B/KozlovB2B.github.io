<?php

namespace app\modules\script\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * Allow for head manager update only own call end reason
 *
 * Class CallEndReasonUpdateOwnRule
 * @package app\modules\site\rbac
 */
class CallEndReasonUpdateOwnRule extends Rule
{
    public $name = 'callEndReasonUpdateOwn';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            if ($params["call-end-reason"]->account_id == $user) {
                return true;
            }
        }

        return false;
    }
}