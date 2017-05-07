<?php

namespace app\modules\script\controllers;

use app\modules\user\models\profile\Operator;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\core\components\CoreController;
use app\modules\script\models\SipAccount;
use yii\helpers\Html;

/**
 * SipAccountController implements the CRUD actions for Script model.
 */
class SipAccountController extends CoreController
{

    /**
     * @var string Model
     */
    protected $_modelClass = 'app\modules\script\models\SipAccount';

    /**
     * Lists all Script models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess("script___sip_account__manage_children");

        $data_provider = new ActiveDataProvider([
            'query' => Operator::find()->byHead(Yii::$app->getUser()->getId()),
        ]);

        return $this->render('index', [
            'data_provider' => $data_provider
        ]);
    }


    /**
     * Update sip account
     *
     *
     * @param null $id
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id = null)
    {
        if (!$id) {
            $id = Yii::$app->getUser()->getId();
        }

        $model = SipAccount::findOrCreate($id);

        $this->checkAccess("script___sip_account__manage", ['sip-account' => $model]);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());

            $this->ajaxValidation($model);

            if ($model->save()) {
                return $this->result(Yii::t('site', 'Saved!'));
            } else {
                return $this->throwException(Html::errorSummary($model));
            }
        }

        return $this->renderPartial('_update_modal', [
            'model' => $model
        ]);
    }

    public function actionTestCall()
    {

    }
}
