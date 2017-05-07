<?php
namespace app\modules\site\controllers;

use app\modules\user\models\User;
use app\modules\site\rbac\rules\CallEndReasonUpdateOwnRule;
use app\modules\site\rbac\rules\UserOperatorUpdateChildrenRule;
use app\modules\site\rbac\rules\ScriptUpdateOwnRule;
use app\modules\site\rbac\rules\BillingScriptExportAllowedRule;
use app\modules\site\rbac\rules\BillingChangeRateRule;
use app\modules\site\rbac\rules\UserOperatorCreateRule;
use app\modules\site\rbac\rules\InvoiceManageOwnRule;
use app\modules\site\rbac\rules\CanExecuteScriptRule;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class RbacController extends Controller
{
    /**
     * @param null $command
     * @return int
     */
    public function actionIndex($command = null)
    {
        echo "Добро пожаловать в контроллер разграничения прав доступа." . PHP_EOL;

        return 0;
    }

    /**
     * User become an admin
     *
     * php yii rbac/make-admin asdfasf@asdf.ru
     *
     * @param $username
     * @return int
     * @throws \yii\db\Exception
     */
    public function actionMakeAdmin($username)
    {
        /** @var User $user */
        $user = User::find()->where('username = :username', ['username' => $username])->one();
        if (!$user) {
            $this->stderr('User ' . $username . ' not found!', Console::FG_RED);
            return 1;
        }

        /** @var \yii\rbac\DbManager $auth */
        $auth = Yii::$app->authManager;

        Yii::$app->getDb()->createCommand()
            ->update($auth->assignmentTable, [
                'item_name' => 'admin'
            ], 'user_id = :user_id', ['user_id' => $user->id])
            ->execute();

        $this->stdout('User ' . $username . ' now admin!', Console::FG_GREEN);
        $this->stdout("\n");
        return 0;
    }

    public function actionInit()
    {
        /** @var \yii\rbac\DbManager $auth */
        $auth = Yii::$app->authManager;
        \Yii::$app->db->createCommand()->delete($auth->itemChildTable)->execute();
        \Yii::$app->db->createCommand()->delete($auth->itemTable)->execute();
        \Yii::$app->db->createCommand()->delete($auth->ruleTable)->execute();
        $auth->invalidateCache();

        // Creating roles

        // Admins
        $god = $auth->createRole('god');
        $auth->add($god);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($god, $admin);

        // Users
        $user_head_manager = $auth->createRole('user_head_manager');
        $auth->add($user_head_manager);
        $auth->addChild($god, $user_head_manager);

        $user_manager = $auth->createRole('user_manager');
        $auth->add($user_manager);
        $auth->addChild($user_head_manager, $user_manager);

        $user_operator = $auth->createRole('user_operator');
        $auth->add($user_operator);
        $auth->addChild($user_manager, $user_operator);

        /**
         * Admin module permissions
         */
        $p = $auth->createPermission('admin___access');
        $p->description = 'Access admin features';
        $auth->add($p);
        $auth->addChild($admin, $p);

        /**
         * Site module permissions
         */
        $p = $auth->createPermission('site___user_operator__manage');
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p_create_user_operator = $auth->createPermission('site___user_operator__create');
        $auth->add($p_create_user_operator);
        $auth->addChild($admin, $p_create_user_operator);

        $p_update_user_operator = $auth->createPermission('site___user_operator__update');
        $auth->add($p_update_user_operator);

        $rule = new UserOperatorUpdateChildrenRule();
        $auth->add($rule);
        $p = $auth->createPermission('site___user_operator__update_own');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($p, $p_update_user_operator);
        $auth->addChild($user_head_manager, $p);

        $rule = new UserOperatorCreateRule();
        $auth->add($rule);
        $p = $auth->createPermission('site___user_operator__create_own');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($p, $p_create_user_operator);
        $auth->addChild($user_head_manager, $p);

        /**
         * Scripts module permissions
         */
        $p_update_script = $auth->createPermission('script___script__update');
        $auth->add($p_update_script);

        $rule = new ScriptUpdateOwnRule();
        $auth->add($rule);
        $p = $auth->createPermission('script___script__update_own');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($p, $p_update_script);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('script___script__create');
        $p->description = 'Create scripts';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('script___script__index');
        $p->description = 'Index scripts';
        $auth->add($p);
        $auth->addChild($user_manager, $p);

        $p_export_script = $auth->createPermission('script___script__export');
        $auth->add($p_export_script);

        $rule = new BillingScriptExportAllowedRule();
        $auth->add($rule);
        $p = $auth->createPermission('script___script__export_own');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($p, $p_export_script);
        $auth->addChild($user_head_manager, $p);


        $rule = new BillingChangeRateRule();
        $auth->add($rule);
        $p = $auth->createPermission('billing__rate__change');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('script___call_end_reason__manage');
        $p->description = 'Manage call end reasons';
        $auth->add($p);
//        $auth->addChild($user_head_manager, $p);

        $p_update_call_end_reason = $auth->createPermission('script___call_end_reason__update');
        $auth->add($p_update_call_end_reason);

        $rule = new CallEndReasonUpdateOwnRule();
        $auth->add($rule);
        $p = $auth->createPermission('script___call_end_reason__update_own');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($p, $p_update_call_end_reason);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('script___call__view');
        $p->description = 'View calls';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);


        // view invoices
        $p_view_invoice = $auth->createPermission('billing___invoice__manage');
        $auth->add($p_view_invoice);
        $rule = new InvoiceManageOwnRule();
        $auth->add($rule);
        $p = $auth->createPermission('billing___invoice__manage_own');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($p, $p_view_invoice);
        $auth->addChild($user_head_manager, $p);
        $auth->addChild($admin, $p_view_invoice);


        $rule = new CanExecuteScriptRule();
        $auth->add($rule);
        $p = $auth->createPermission('script___call__perform');
        $p->ruleName = $rule->name;
        $auth->add($p);
        $auth->addChild($user_operator, $p);


        $p = $auth->createPermission('script___call__statistics');
        $p->description = 'Calls statistic';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('script___report__view');
        $p->description = 'View reports';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('billing___account__manage');
        $p->description = 'Manage billing account';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('billing__rate__set');
        $p->description = 'Set user rate';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('billing___account__manage_own');
        $p->description = 'Manage own billing account';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('aff___account__manage_own');
        $p->description = 'Manage own affiliate account';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('aff___hit__index');
        $p->description = 'View hits';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('aff___promo_link__index');
        $p->description = 'View hits';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

//        $p = $auth->createPermission('integration___integration__manage');
//        $p->description = 'Integrations manage';
//        $auth->add($p);
//        $auth->addChild($user_head_manager, $p);


        $p = $auth->createPermission('site___instruction__manage');
        $p->description = 'Manage user instructions';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('site___instruction__view');
        $p->description = 'View user instructions';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);




        $p = $auth->createPermission('user___account__manage');
        $p->description = 'Manage users';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('user___account__profile');
        $p->description = 'View own profile';
        $auth->add($p);
        $auth->addChild($user_head_manager, $p);

        $p = $auth->createPermission('billing___rate__manage');
        $p->description = 'Manage rates';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('billing___balance_operations__index_all');
        $p->description = 'Index all balance operations';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('billing___rate_change_history__index_all');
        $p->description = 'Index all rate_change_history';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('billing___use_withdraw__index');
        $p->description = 'Index use withdraw procedures';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('sales___access');
        $p->description = 'Sales module access';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('script___script_export_log__index');
        $p->description = 'View script export attempts';
        $auth->add($p);
        $auth->addChild($admin, $p);

        $p = $auth->createPermission('blog___blog__admin');
        $p->description = 'Admin access to blog';
        $auth->add($p);
        $auth->addChild($admin, $p);
    }
}