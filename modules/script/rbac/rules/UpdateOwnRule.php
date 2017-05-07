<?php

namespace app\modules\script\rbac\rules;

use Yii;
use yii\rbac\Rule;
use app\modules\user\models\UserHeadManager;

/**
 * Allow for head manager update only own call end reason
 *
 * Class ScriptUpdateOwnRule
 * @package app\modules\site\rbac
 */
class UpdateOwnRule extends Rule
{
    public $name = 'updateOwn';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $head_manager = UserHeadManager::findHeadManagerByUser();

            if ($params["model"]->account_id == $head_manager->id) {
                return true;
            }
        }

        return false;
    }
}