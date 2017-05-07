<?php

namespace app\modules\script\controllers;

use app\modules\billing\models\Account as BillingAccount;
use app\modules\core\components\CoreController;
use app\modules\core\components\Publishable;
use app\modules\core\components\Url;
use app\modules\script\components\ScriptConverter;
use app\modules\script\models\ar\EditorSession;
use app\modules\script\models\ar\Release;
use app\modules\script\models\ar\Script;
use app\modules\script\models\form\EditorOptionsForm;
use app\modules\script\models\form\ImportForm;
use app\modules\script\models\form\PerformerOptionsForm;
use app\modules\script\models\ScriptExportLog;
use app\modules\user\models\UserHeadManager;
use app\modules\user\models\profile\Operator;
use romi45\findModelTrait\FindModelTrait;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\site\models\MultiSessionGuard;
use app\modules\script\components\V2Importer;

/**
 * ScriptController implements the CRUD actions for Script model.
 *
 * @method Script findModel($id, $class = null) see [[FindModelTrait::findModel()]] for more info
 */
class ScriptController extends CoreController
{
    use FindModelTrait;

    /**
     * @var string Model
     */
    protected $_modelClass = 'app\modules\script\models\ar\Script';

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['import', 'delete', 'convert', 'clone', 'data'],
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
    public function actionAdmin()
    {
        $this->checkAccess("script___script__admin");

        if (BillingAccount::isBlocked()) {
            $this->redirect('/billing');
        }

        $scripts_data_provider = new ActiveDataProvider([
            'query' => Script::find()->activeByUserCriteria(\Yii::$app->getUser()->getId()),
        ]);


        return $this->render('index', [
            'scripts_data_provider' => $scripts_data_provider
        ]);
    }

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess("script___script__index");

        if (BillingAccount::isBlocked()) {
            $this->redirect('/billing');
        }

        $scripts_data_provider = new ActiveDataProvider([
            'query' => Script::find()->activeByUserCriteria(\Yii::$app->getUser()->getId()),
        ]);


        return $this->render('index', [
            'scripts_data_provider' => $scripts_data_provider
        ]);
    }

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionDashboardList()
    {
        $this->checkAccess("script___script__index");

        if (BillingAccount::isBlocked()) {
            $this->redirect('/billing');
        }

        $hm = UserHeadManager::findHeadManagerByUser();

        $scripts_data_provider = new ActiveDataProvider([
            'query' => Script::find()->activeByUserCriteria($hm->id),
        ]);


        return $this->renderAjax('_dashboard_list', [
            'scripts_data_provider' => $scripts_data_provider
        ]);
    }


    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionOperatorList()
    {
        $this->checkAccess("script___release__index");

        if (BillingAccount::isBlocked()) {
            $this->redirect('/billing');
        }


        return $this->renderAjax('_operator_list');
    }


    /**
     * @return Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $this->checkAccess("script___script__create");

        $head_manager = UserHeadManager::findHeadManagerByUser();

        $model = new Script();
        $model->name = Yii::t('script', 'New script');
        $model->max_node = 0;
        $model->status_id = Publishable::STATUS_DRAFT;
        $model->v2converted = 1;
        $model->user_id = $head_manager->id;

        if ($model->save()) {
            return $this->redirect(Url::to(['/script/script/edit', 'id' => $model->id]));
        } else {
            throw new Exception('Не удалось создать скрипт: ' . implode(',', $model->getFirstErrors()));
        }
    }

    /**
     * Updates an existing Script model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $focus_node Node to be focused
     * @return mixed
     */
    public function actionEdit($id, $focus_node = null)
    {
        $model = $this->findModel($id);

        $this->checkAccess("script___script__update", ['script' => $model]);

        if (!$model->v2converted) {
            ScriptConverter::convert($model->id);
            $model->refresh();
        }

        $hm = UserHeadManager::findHeadManagerByUser();

        return $this->render('edit', [
            'model' => $model,
            'session' => EditorSession::start($model->id),
            'default_editor_options' => $hm->editor_options,
            'focus_node' => $focus_node,
            'hm' => $hm,
        ]);
    }


    /**
     * Настройки прогонщика
     *
     * @param $id
     * @return string
     */
    public function actionPerformerOptions($id)
    {

        $script = $this->findModel($id);

        $this->checkAccess("script___script__update", ['script' => $script]);

        $model = new PerformerOptionsForm($script);

        $saved = false;

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $saved = $model->save();
        }

        return $this->renderAjax('_performer_options_modal', ['model' => $model, 'saved' => $saved]);
    }

    /**
     * Настройки прогонщика
     *
     * @param $id
     * @return string
     */
    public function actionEditorOptions($id)
    {

        $script = $this->findModel($id);

        $this->checkAccess("script___script__update", ['script' => $script]);

        $hm = UserHeadManager::findHeadManagerByUser();

        $model = new EditorOptionsForm($script, $hm);

        $saved = false;

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $saved = $model->save();
        }

        return $this->renderAjax('_editor_options_modal', ['model' => $model, 'saved' => $saved]);
    }

    /**
     * Updates an existing Script model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $focus_node Node to be focused
     * @return mixed
     */
    public function actionConvert($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("script___script__update", ['script' => $model]);

        if (!$model->v2converted) {
            ScriptConverter::convert($model->id);

            return true;
        } else {
            throw new Exception('Скрипт уже сконвертирован!');
        }
    }

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionConversion()
    {
        $this->checkAccess("script___script__index");

        $scripts_data_provider = new ActiveDataProvider([
            'query' => Script::find()->byAccount(Yii::$app->getUser()->getId())->notConverted(),
        ]);

        if (!$scripts_data_provider->getTotalCount()) {
            return $this->redirect('/');
        }

        return $this->render('conversion', [
            'scripts_data_provider' => $scripts_data_provider
        ]);
    }

    /**
     * Show user export restricted modal
     *
     * @param $id
     * @return string
     * @throws \yii\base\ErrorException
     */
    public function actionExportRestricted($id)
    {
        $model = $this->findModel($id);

        ScriptExportLog::write($model, 0);

        return $this->renderPartial('_export_restricted_modal');
    }


    /**
     * Export current script data to file
     *
     * @param $id
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function actionExport($id)
    {
        $model = $this->findModel($id);

        ScriptExportLog::write($model, Yii::$app->getUser()->can("script___script__export", ['script' => $model]));

        $this->checkAccess("script___script__export", ['script' => $model]);

        if (!$model->v2converted) {
            ScriptConverter::convert($model->id);

            $model->refresh();
        }

        Yii::$app->getResponse()->sendContentAsFile($model->getExportData(), $model->name . '.' . Script::SCRIPT_FILE_EXTENSION)->send();
    }


    /**
     * Export current script data to file
     *
     * @param $id
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function actionExportAdmin($id)
    {
        $model = $this->findModel($id);

        if (!$model->v2converted) {
            ScriptConverter::convert($model->id);

            $model->refresh();
        }

        Yii::$app->getResponse()->sendContentAsFile($model->getExportData(), $model->name . '.' . Script::SCRIPT_FILE_EXTENSION)->send();
    }

    /**
     * Импорт скрипта
     *
     * @return array|bool
     * @throws Exception
     */
    public function actionImport()
    {
        $this->checkAccess("script___script__create");

        $model = new ImportForm();

        $file = UploadedFile::getInstance($model, 'file');

        if ($file) {
            $model->file = $file;

            if ($imported = $model->import()) {

                ScriptExportLog::write($imported, 1, ScriptExportLog::TYPE_IMPORT);

                return ['status' => 'ok', 'url' => Url::to(['/script/script/edit', 'id' => $imported->id])];
            } else {
                throw new Exception(implode(',', $model->getFirstErrors()));
            }
        } else {
            throw new Exception('Загрузите файл!');
        }
    }

    /**
     * Клонирует скрипт
     *
     * @param $id
     * @return Script
     * @throws Exception
     */
    public function actionClone($id)
    {
        $this->checkAccess("script___script__create");

        $model = $this->findModel($id);

        if (!$model->v2converted) {
            ScriptConverter::convert($model->id);

            $model->refresh();
        }

        return V2Importer::import(json_decode($model->getBuild(), true));
    }

    /**
     *
     * @param $id
     * @param $mode
     * @return string
     * @throws Exception
     */
    public function actionData($id, $mode = 'call')
    {
        $model = $this->findModel($id);

        $head = UserHeadManager::findHeadManagerByUser();

        if (!Yii::$app->getUser()->can("script___call__perform", ['script' => $model])) {

            /** @var BillingAccount $billing */
            $billing = BillingAccount::findOne($model->user_id);

            Yii::$app->response->setStatusCode(403);

            return [
                'message' => $billing->executionsLimitErrorMessage()
            ];
        }

        if (MultiSessionGuard::checkOtherSessions(Yii::$app->getUser()->getId())) {
            Yii::$app->response->setStatusCode(403);
            return [
                'message' => $this->renderPartial('@app/modules/site/views/multi-session-guard/ask_terminate_other_sessions_exception', ['model' => MultiSessionGuard::create(Yii::$app->getUser()->getId())])
            ];
        }

        $latest_build = json_decode($model->getBuild(), true);

        if ($mode == 'test') {
            if(Yii::$app->getRequest()->get('release_id')){
                return $latest_build;
            }

            return [
                'release_id' => 'test',
                'build' => $latest_build
            ];
        } else {
            $release = $model->release;

            if ($head->create_builds_manually) {
                if (!$release) {
                    Yii::$app->response->setStatusCode(403);
                    return [
                        'message' => 'Чтобы выполнять звонки с записью статистики - опубликуйте скрипт.'
                    ];
                }
            } else if (!$release || $release->build_md5 != $model->build_md5) {
                $release = Release::autoCreate($model);
            }

            $release_build = json_decode($release->build, true);

            $release_build['script']['performer_options'] = !empty($latest_build['script']['performer_options']) ? $latest_build['script']['performer_options'] : null;

            if(Yii::$app->getRequest()->get('release_id')){
                return $latest_build;
            }

            return [
                'release_id' => $release->id,
                'build' => $release_build
            ];
        }
    }

    /**
     * Loading script data for performing a call
     *
     * @param int $id Script id
     * @throws NotFoundHttpException
     */
    public function actionLoadCallData($id, $test = 0)
    {
        $model = $this->findModel($id);

        if (!\Yii::$app->getUser()->can("script___call__perform", ['script' => $model])) {
            /** @var BillingAccount $billing */
            $billing = BillingAccount::findOne(Yii::$app->user->getId());
            echo json_encode(['message' => $billing->executionsLimitErrorMessage()]);
            exit;
        }


        $result = [];
        $result['script'] = json_decode($model->data_json, true);
        $result["start_node"] = $model->start_node_id;
        $result["version"] = $model->current_version;
        $result["script_name"] = $model->name;
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->getUser()->can("user_head_manager")) {
            $user_id = \Yii::$app->getUser()->getId();
        } else {
            $op = $this->findOperatorModel(\Yii::$app->getUser()->getId());
            $user_id = $op->head_id;
        }

        if ($test) {
            UserHeadManager::incrementTestExecutionsToday($user_id);
        } else {
            UserHeadManager::incrementExecutionsToday($user_id);
        }

        echo json_encode($result);
    }

    /**
     * Loading script data for performing a call
     *
     * @param int $id Script id
     * @throws NotFoundHttpException
     */
    public function actionIncrementTestExecutions($id)
    {
        $model = $this->findModel($id);

        if (!\Yii::$app->getUser()->can("script___call__perform", ['script' => $model])) {
            /** @var BillingAccount $billing */
            $billing = BillingAccount::findOne(Yii::$app->user->getId());
            echo json_encode(['message' => $billing->executionsLimitErrorMessage()]);
            exit;
        }

        UserHeadManager::incrementTestExecutionsToday($model->user_id);
        echo json_encode(['code' => 200]);
        exit;
    }

    /**
     * Deletes an existing Script model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);

        $this->checkAccess("script___script__delete", ['script' => $model]);

        $model->safeDelete();

        return true;
    }

    /**
     * Finds the Operator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findOperatorModel($id)
    {
        if (($model = Operator::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested operator does not exist.');
        }
    }
}
