<?php

namespace app\modules\user\rbac\rules;

use app\modules\user\models\profile\ProfileRelation;
use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\Rule;
use app\modules\user\models\User;

/**
 * Allow for head manager update only own call end reason
 *
 * Class FlowUpdateOwnRule
 * @package app\modules\user\rbac
 */
class UserUpdateNotOwnerOrAdminRule extends Rule
{
    public $name = 'UserUpdateNotOwnerOrAdmin';

    public function execute($user, $item, $params)
    {
        if (!isset($params["user"])) {
            throw new InvalidConfigException('Укажите экземпляк класса  app\modules\user\models\User как $params["user"].');
        }

        if (!\Yii::$app->user->isGuest) {
            /** @var User $user */
            $user = $params["user"];

            foreach ($user->profileRelations as $r) {
                if ($r->profile_class == ProfileRelation::PROFILE_CLASS_ADMIN || $r->profile_class == ProfileRelation::PROFILE_CLASS_OWNER) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
