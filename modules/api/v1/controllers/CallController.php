<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\models\ApiUser;
use app\modules\api\v1\components\BaseController;
use app\modules\api\v1\components\CallBackPerformer;
use app\modules\core\components\Url;
use app\modules\integration\components\Performer as IntegrationPerformer;
use app\modules\script\models\ar\Script;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\modules\script\models\Call;
use app\modules\user\models\UserHeadManager;
use yii\helpers\Html;
use app\modules\site\models\MultiSessionGuard;
use yii\web\UploadedFile;
use app\modules\script\components\UploadCallRecordForm;

/**
 * ScriptController
 */
class CallController extends BaseController
{
    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->initGui();
    }

    /**
     * https://scriptdesigner.ru/api/v1/call/perform?key=Q003jtbDcc14aVimH8AxdQtCEFob_TZbtsnTb-jbAS-biANhbxSxAFAidZAWVDDo&script_id=6810&callback=http%3A%2F%2Fcap.sugar-crm.ru%2Fvoice_script.php%3Fuser%3D944cab16-ef60-4c4d-1c82-49c897ca8517%26module%3DAccounts%26record%3D3452_1%26callnumber%3D%26name%3D%D0%9D%D0%B0%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%BD%D0%B8+%D0%B2%D1%81%D1%82%D1%80%D0%B5%D1%87%D0%B8+%D0%A7%D0%93%D0%9F%26is_goal%3D_is_goal_reached_%26is_normal%3D_normal_ending_%26comment%3D_comment_
     *
     * Выполняет звонок по скрипту.
     *
     * @param int $script_id ID сктипта
     * @param null $user_login Логин пользователя API
     * @param null $callback URL, который будет вызван по окончанию звонка
     * @throws NotFoundHttpException если указываем скрипт, не пренадлежащий авторизованному пользователю
     * @return string
     */
    public function actionPerform($script_id, $user_login = null, $callback = null)
    {
        Yii::$app->response->format = Response::FORMAT_HTML;

        $script = $this->findScriptModel($script_id);

        if ($script->user_id != $this->_user_head_manager->id) {
            throw new NotFoundHttpException($script->user_id);
        }

        UserHeadManager::incrementExecutionsToday($this->_user_head_manager->id);

        $perform_url = Url::to(['/api/v1/call/perform', 'script_id' => $script_id, 'key' => Yii::$app->request->get('key')], true);
        $decline_url = Url::to(['/api/v1/script/index']);

        if (Yii::$app->getUser()->getId()) {
            if (MultiSessionGuard::checkOtherSessions(Yii::$app->getUser()->getId())) {
                return $this->redirect(Url::to(['/site/multi-session-guard/ask-terminate-other-sessions-api', 't' => MultiSessionGuard::create(Yii::$app->getUser()->getId())->token, 'redirect' => $perform_url, 'decline_url' => $decline_url]));
            }
        }

        $model = new Call();
        $model->script_id = $script->id;
        $model->api_user = $user_login;

        return $this->render('perform', [
            'script' => $script,
            'model' => $model,
            'key' =>  Yii::$app->request->get('key'),
            'action' => Url::to(['/api/v1/call/end', 'key' => Yii::$app->request->get('key'), 'callback' => $callback])
        ]);
    }

    /**
     * ы
     *
     * @return string
     */
    public function actionResult($id)
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        return $this->render('result', ['model' => $this->findCallModel($id)]);
    }

    /**
     * Добавляет ссылку на звуковую запись звонка к звонку
     *
     * @param int $id
     * @param string $url
     * @return string
     */
    public function actionAttachRecord($id, $url)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findCallModel($id);

        $model->record_url = $url;

        if ($model->update(true, ['record_url'])) {
            return ['status' => 200];
        } else {
            return ['status' => 400, 'errors' => $model->getErrors()];
        }
    }


    /**
     * Завершение звонка
     *
     * @param $callback
     * @return array|string
     */
    public function actionEnd($callback = null)
    {
        $model = new Call();
        $model->load(Yii::$app->request->post());
        $model->using_api = 1;
        $model->user_id = Yii::$app->getUser()->getId() ? Yii::$app->getUser()->getId() : $this->_user_head_manager->id;
        $model->account_id = $this->_user_head_manager->id;
        $model->duration = $model->ended_at - $model->started_at;
        $this->ajaxValidation($model);
        $model->fillDeNormalizedStat();

        if ($model->api_user) {
            $api_user = ApiUser::find()->byAccount($this->_user_head_manager->id)->byLogin($model->api_user)->one();

            if (!$api_user) {
                $api_user = new ApiUser();
                $api_user->user_login = $model->api_user;
                $api_user->account_id = $this->_user_head_manager->id;
                $api_user->save(false);
            }
        }

        if ($model->save()) {

            $record_errors = null;

            if (!empty($_FILES['record'])) {

                $upload = new UploadCallRecordForm();

                $upload->record = UploadedFile::getInstanceByName('record');

                if ($record_file = $upload->upload($model->account_id)) {
                    $model->record_url = $record_file;
                    $model->update(false, ['record_url']);
                } else {
                    $record_errors = Html::errorSummary($model);
                }
            }


            IntegrationPerformer::perform($model);
            CallBackPerformer::perform($model, $callback);

            return $this->result(Yii::t('script', 'Call completed!') . ' ' . $record_errors, ['id' => $model->id]);
        } else {
            return $this->throwException(Html::errorSummary($model));
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
        if (($model = Script::find()->byUser($this->_user_head_manager->id)->byId($id)->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Такой скрипт не найден!');
        }
    }


    /**
     * Finds the Script model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Call the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCallModel($id)
    {
        if (($model = Call::find()->byAccount($this->_user_head_manager->id)->byId($id)->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Такой звонок не найден!');
        }
    }
}