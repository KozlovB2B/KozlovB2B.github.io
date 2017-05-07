<?php
namespace app\modules\script\controllers;

use app\modules\script\components\editorCommand\CommandInvoker;
use app\modules\script\models\ar\EditorSession;
use app\modules\core\components\BaseCoreController;
use app\modules\script\models\ar\Script;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class EditorController
 * @package app\modules\script\controllers
 */
class EditorController extends BaseCoreController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create', 'undo', 'redo'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }


    /**
     * Получает номер для нового узла
     *
     * @param $script_id
     * @return int
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionNodeNumber($script_id)
    {
        $this->checkAccess("script___script__create");

        $current_max_node = (int)Yii::$app->getDb()->createCommand('SELECT max_node FROM ' . Script::tableName() . ' WHERE id = :id', [':id' => $script_id])->queryScalar();

        $current_max_node++;

        Yii::$app->getDb()->createCommand('UPDATE ' . Script::tableName() . ' SET max_node = ' . $current_max_node . ' WHERE id = :id', [':id' => $script_id])->execute();

        return $current_max_node;
    }

    /**
     * Выполняет новую команду
     *
     * @param $session_id
     * @throws NotFoundHttpException
     * @return boolean
     */
    public function actionCreate($session_id)
    {
        $this->checkAccess("script___script__create");

        CommandInvoker::create($this->findSessionModel($session_id), Yii::$app->getRequest()->post('create'));

        return true;
    }


    /**
     * Отменяет действие
     *
     * @param $session_id
     * @param $command_id
     * @throws NotFoundHttpException
     * @return boolean
     */
    public function actionUndo($session_id, $command_id)
    {
        $this->checkAccess("script___script__create");

        CommandInvoker::undo($this->findSessionModel($session_id), $command_id);

        return true;
    }

    /**
     * Повторно выполняет действие
     *
     * @param $session_id
     * @param $command_id
     * @throws NotFoundHttpException
     * @return boolean
     */
    public function actionRedo($session_id, $command_id)
    {
        $this->checkAccess("script___script__create");

        CommandInvoker::redo($this->findSessionModel($session_id), $command_id);

        return true;
    }

    /**
     * @param int $id
     * @return EditorSession
     * @throws NotFoundHttpException
     */
    protected function findSessionModel($id)
    {
        if (($model = EditorSession::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested session does not exist.');
        }
    }
}