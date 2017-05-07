<?php

namespace app\modules\integration\modules\zebra\controllers;

use app\modules\core\components\CoreController;
use app\modules\integration\modules\zebra\models\UserSettings;
use Yii;

/**
 * UserSettingsController implements the CRUD actions for ApiCredentials model.
 */
class UserSettingsController extends CoreController
{

    /**
     * @param $id
     * @param $value
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSave($id)
    {
        $this->checkAccess('integration___integration__manage');

        /** @var UserSettings $model */
        $model = UserSettings::findOne(['user_id' => $id]);

        if (!$model) {
            $model = Yii::createObject([
                'class' => UserSettings::className(),
                'user_id' => $id
            ]);
        }

        $model->number = Yii::$app->request->get('number');
        $model->name =  Yii::$app->request->get('name');

        $model->save();
    }
}
