<?php

namespace app\modules\script\controllers;

use app\modules\script\models\ScriptExportLog;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\core\components\CoreController;

/**
 * ScriptExportLogController implements the CRUD actions for Script model.
 */
class ScriptExportLogController extends CoreController
{

    /**
     * View script export attempts
     *
     * @param boolean $as_excel
     * @return string
     */
    public function actionIndex($as_excel = false)
    {
        $this->checkAccess("script___script_export_log__index");

        $search = new ScriptExportLog();
        $search->load(Yii::$app->request->get());

        $query = ScriptExportLog::find();
        $query->andFilterWhere(['type_id' => $search->type_id]);


        $data_provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        if($as_excel){

            ScriptExportLog::asExcel($data_provider);

            return false;
        }


        return $this->render('index', [
            'data_provider' => $data_provider,
            'search' => $search,
        ]);
    }
}
