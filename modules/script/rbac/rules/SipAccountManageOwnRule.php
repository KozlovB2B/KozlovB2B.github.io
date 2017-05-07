<?php

namespace app\modules\script\rbac\rules;

use Yii;
use yii\rbac\Rule;
use app\modules\user\models\profile\Operator;

/**
 * Allow for head manager update only own call end reason
 *
 * Class SipAccountManageOwnRule
 * @package app\modules\site\rbac
 */
class SipAccountManageOwnRule extends Rule
{
    public $name = 'sipAccountManageOwnRule';

    public function execute($user, $item, $params)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (empty($params["sip-account"])) {
            return true;
        }

        if ($params["sip-account"]->id == $user) {
            return true;
        }

        /** @var Operator $op */
        $op = Operator::findOne($params["sip-account"]->id);

        if (!$op) {
            return false;
        }

        if ($op->head_id == $user) {
            return true;
        }

        return false;
    }
}