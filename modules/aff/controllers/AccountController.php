<?php

namespace app\modules\aff\controllers;

use romi45\findModelTrait\FindModelTrait;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\aff\components\AffBaseController;
use app\modules\aff\models\Account;

/**
 * AccountController implements the CRUD actions for Script model.
 *
 * @method Account findModel($id, $class = null) see [[FindModelTrait::findModel()]] for more info
 */
class AccountController extends AffBaseController
{
    use FindModelTrait;

    /**
     * @var string Model class name for findModel($id, $class = null)
     */
    protected $_modelClass = 'app\modules\aff\models\Account';


    /**
     * Manage aff account
     *
     * @return string
     * @throws \Exception
     */
    public function actionManage()
    {
        $this->checkAccess("aff___account__manage_own");

        $account = Account::current();

        if (Yii::$app->request->post()) {
            $account->setScenario('accept_terms');
            $account->load(Yii::$app->request->post());
            if ($account->update(true, ['terms_accepted'])) {
                $this->redirect('/aff');
            }
        }

        return $this->render('manage', ['account' => $account]);
    }

    /**
     * Terms of user
     *
     * @return string
     */
    public function actionTerms()
    {
        $this->checkAccess("aff___account__manage_own");

        return $this->render('terms', ['account' => Account::current()]);
    }

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionAttractedUsers()
    {
        $this->checkAccess("aff___account__manage_own");

        return $this->render('attracted_users', ['data_provider' => new ActiveDataProvider([
            'query' => Account::find()->activeByUserCriteria(\Yii::$app->getUser()->getId()),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ])]);
    }
}
