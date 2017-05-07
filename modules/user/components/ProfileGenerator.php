<?php

namespace app\modules\user\components;

use app\modules\user\models\profile\Admin;
use app\modules\user\models\profile\Head;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\profile\ProfileRelation;
use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;
use app\modules\user\models\UserOperator;
use Yii;
use yii\base\Exception;

/**
 * Class ProfileGenerator
 *
 * Генерирует для пользователя профиль. если у него его еще нет
 *
 * @package app\modules\user\components
 */
class ProfileGenerator
{
    /**
     * @throws Exception
     */
    public static function generate()
    {
        // переводит пользователя, если он еще отмечен как перешедший
        if (!Yii::$app->getUser()->identity->v2) {
            return false;
//            Yii::$app->getUser()->identity->v2 = 1;
//            Yii::$app->getUser()->identity->update(false, ['v2']);
        }

        $profile_old = OldProfile::findOne(Yii::$app->getUser()->getId());

        if (!$profile_old) {
            throw new Exception('Не найден OldProfile' . Yii::$app->getUser()->getId());
        }


        $relation = new ProfileRelation();
        $relation->is_current = 1;
        $relation->user_id = Yii::$app->getUser()->getId();

        if (Yii::$app->getUser()->can("admin")) {
            static::generateAdmin($relation, $profile_old);
        } elseif (Yii::$app->getUser()->can("user_head_manager")) {
            static::generateHead($relation, $profile_old);
        } elseif (Yii::$app->getUser()->can("user_operator")) {
            static::generateOperator($relation, $profile_old);
            return;
        }

        if (!$relation->save(false)) {
            throw new Exception(implode(',', $relation->getFirstErrors()));
        }
    }

    /**
     * @param ProfileRelation $relation
     * @param OldProfile $profile_old
     * @throws Exception
     */
    protected static function generateAdmin(ProfileRelation &$relation, OldProfile $profile_old)
    {
        $relation->profile_class = 'Admin';

        $admin = new Admin();

        $admin->user_id = $relation->user_id;
        $admin->first_name = $profile_old->name;

        if (!$admin->save(false)) {
            throw new Exception(implode(',', $admin->getFirstErrors()));
        }
    }

    /**
     * @param ProfileRelation $relation
     * @param OldProfile $profile_old
     * @throws Exception
     */
    protected static function generateHead(ProfileRelation &$relation, OldProfile $profile_old)
    {
        $relation->profile_class = 'Head';

        /** @var UserHeadManager $hm */
        $hm = UserHeadManager::findOne($relation->user_id);

        if (!$hm) {
            throw new Exception('Не найден UserHeadManager' . $relation->user_id);
        }
        /**
         *
        2016-11-09 11:03:54 [5.35.117.229][3115][-][error][yii\db\IntegrityException] exception 'PDOException' with message 'SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`ss`.`profile_operator`, CONSTRAINT `fk-profile_operator-head` FOREIGN KEY (`head_id`) REFERENCES `profile_head` (`user_id`) ON DELETE CASCADE)' in /home/ss/apps/ssv2/vendor/yiisoft/yii2/db/Command.php:844
        Stack trace:

         */

        $head = new Head();
        $head->user_id = $relation->user_id;
        $head->accept_terms = 1;
        $head->first_name = $profile_old->name;
        $head->phone = $hm->phone;

        if (!$head->save(false)) {
            throw new Exception(implode(',', $head->getFirstErrors()));
        }

        /** @var UserOperator[] $operators */
        $operators = UserOperator::find()->where('head_id=:head_id', [':head_id' => $head->id])->all();

        if(!$operators){
            return;
        }

        foreach ($operators as $op) {

            $user = User::findOne($op->id);

            if ($user) {
                $user->v2 = 1;
                $user->update(false, ['v2']);

                $rel = new ProfileRelation();
                $rel->is_current = 1;
                $rel->user_id = $op->id;
                $rel->profile_class = 'Operator';

                if (!$rel->save(false)) {
                    throw new Exception(implode(',', $rel->getFirstErrors()));
                }

                $operator = new Operator();
                $operator->head_id = $head->user_id;
                $operator->user_id = $rel->user_id;
                $operator->first_name = $op->first_name;
                $operator->last_name = $op->last_name;

                if (!$operator->save(false)) {
                    throw new Exception(implode(',', $operator->getFirstErrors()));
                }
            }
        }
    }

    /**
     * @param ProfileRelation $relation
     * @param OldProfile $profile_old
     * @throws Exception
     */
    protected static function generateOperator(ProfileRelation &$relation, OldProfile $profile_old)
    {
//        $relation->profile_class = 'Operator';

        /** @var Operator $hm */
        $op = UserOperator::findOne($relation->user_id);

        if (!$op) {
            throw new Exception('Не найден Operator' . $relation->user_id);
        }


//        $relation->profile_class = 'Head';

        /** @var Head $hm */
        $hm = Head::findOne($op->head_id);

        if (!$hm) {
            $profile_old = OldProfile::findOne($op->head_id);

            if (!$profile_old) {
                throw new Exception('Не найден OldProfile' . $op->head_id);
            }

            $relation = new ProfileRelation();
            $relation->is_current = 1;
            $relation->user_id = $op->head_id;

            static::generateHead($relation, $profile_old);

            if (!$relation->save(false)) {
                throw new Exception(implode(',', $relation->getFirstErrors()));
            }
        }



//        $operator = new Operator();
//        $operator->head_id = $op->head_id;
//        $operator->user_id = $relation->user_id;
//        $operator->first_name = $profile_old->name;
//
//        if (!$operator->save(false)) {
//            throw new Exception(implode(',', $operator->getFirstErrors()));
//        }
    }
}