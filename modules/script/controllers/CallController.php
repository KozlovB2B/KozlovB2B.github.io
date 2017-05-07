<?php

namespace app\modules\script\controllers;

use app\modules\billing\models\Account;
use app\modules\script\components\UploadCallRecordForm;
use app\modules\script\models\ar\CallData;
use app\modules\script\models\Call;
use app\modules\script\models\SipAccount;
use app\modules\user\models\UserHeadManager;
use app\modules\user\models\profile\Operator;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use app\modules\core\components\CoreController;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\script\models\ar\Script;
use app\modules\billing\models\Account as BillingAccount;
use app\modules\site\models\MultiSessionGuard;
use yii\web\UploadedFile;
use yii\web\Response;

/**
 * ScriptController implements the CRUD actions for Script model.
 */
class CallController extends CoreController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'end' => ['post'],
                ],
            ],
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['start', 'end', 'report'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess("script___call__view");

        if (Yii::$app->getUser()->can("user_head_manager")) {
            if (Account::isBlocked()) {
                $this->redirect('/billing');
            }

            $data_provider = new ActiveDataProvider([
                'query' => Call::find()->byAccount(\Yii::$app->getUser()->getId()),
            ]);
        } else {
            $data_provider = new ActiveDataProvider([
                'query' => Call::find()->byUser(\Yii::$app->getUser()->getId()),
            ]);
        }

        return $this->render('index', [
            'data_provider' => $data_provider,
        ]);
    }


    /**
     * End call
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionStart($script_id, $release_id)
    {
        $script = $this->findScriptModel($script_id);

        $head_manager = UserHeadManager::findHeadManagerByUser();

        if($head_manager->record_calls){
            Call::createRecordsStorageFolder($head_manager->id);
        }

        if (!Yii::$app->getUser()->can("script___call__perform", ['script' => $script])) {
            /** @var BillingAccount $billing */
            $billing = BillingAccount::findOne($head_manager->id);
            throw new ForbiddenHttpException($billing->executionsLimitErrorMessage());
        }


        $this->checkAccess("script___call__perform", ['script' => $script]);


        if (MultiSessionGuard::checkOtherSessions(Yii::$app->getUser()->getId())) {
            Yii::$app->response->setStatusCode(403);
            return [
                'message' => $this->renderPartial('@app/modules/site/views/multi-session-guard/ask_terminate_other_sessions_exception', ['model' => MultiSessionGuard::create(Yii::$app->getUser()->getId())])
            ];
        }

        if ($release_id == 'test') {
            UserHeadManager::incrementTestExecutionsToday($head_manager->id);

            return 'test';
        } else {
            UserHeadManager::incrementExecutionsToday($head_manager->id);

            $model = new Call();
            $model->script_id = $script->id;
            $model->release_id = $release_id;
            $model->user_id = Yii::$app->getUser()->getId();
            $model->account_id = $head_manager->id;
            $model->started_at = time();
            if (!$model->save()) {
                throw new Exception(implode(',', $model->getFirstErrors()));
            }

            if (Yii::$app->getRequest()->post('data')) {
                CallData::write($model->id, Yii::$app->getRequest()->post('data'));
            }

            $model->trigger(Call::EVENT_AFTER_START);

            return $model->id;
        }
    }



    /**
     * @param $id
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionEnd($id)
    {
        if ($id == 'loading') {
            return true;
        }

        $model = $this->findModel($id);

        $model->setScenario('end');

        $model->load(Yii::$app->request->post());

        if (!$model->validate()) {
            throw new Exception(implode(',', $model->getFirstErrors()));
        }

        $model->ended_at = $model->started_at + $model->duration;

        $model->writeRecordUrl();

        if ($model->save()) {
            $model->trigger(Call::EVENT_AFTER_END);

            return true;
        } else {
            throw new Exception(implode(',', $model->getFirstErrors()));
        }
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionReport($id)
    {
        $model = $this->findModel($id);

        $model->setScenario('report');

        if (!Yii::$app->getUser()->can("script___call__perform", ['script' => $model->script])) {
            /** @var BillingAccount $billing */
            $billing = BillingAccount::findOne($model->account_id);
            $this->throwException($billing->executionsLimitErrorMessage());
        }

        $model->load(Yii::$app->request->post());

        if (!$model->validate()) {
            throw new Exception(implode(',', $model->getFirstErrors()));
        }

        $model->ended_at = $model->started_at + $model->duration;

        $model->writeRecordUrl();

        if ($model->save()) {
            $model->trigger(Call::EVENT_AFTER_REPORT);
            return true;
        } else {
            throw new Exception(implode(',', $model->getFirstErrors()));
        }
    }

    /**
     * View call
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->checkAccess("script___call__view");

        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Listen call record
     *
     * @param int $id
     * @param int $start
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionListen($id, $start = 0)
    {
        $this->checkAccess("script___call__view");

        return $this->renderPartial('_listen_modal', ['model' => $this->findModel($id), 'start' => $start]);
    }

    /**
     * Finds the Script model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Call the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Call::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Script model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Script the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findScriptModel($id)
    {
        if (($model = Script::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
