<?php

namespace app\modules\site\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * Allow for head manager update only own operators
 *
 * Class UserOperatorUpdateChildrenRule
 * @package app\modules\site\rbac
 */
class UserOperatorUpdateChildrenRule extends Rule
{
    public $name = 'userOperatorUpdateChildren';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            if ($params["user-operator"]->head_id == $user) {
                return true;
            }
        }

        return false;
    }
}