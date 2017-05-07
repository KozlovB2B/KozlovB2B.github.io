<?php

namespace app\modules\integration\modules\zebra\controllers;

use app\modules\core\components\CoreController;
use app\modules\script\models\Call;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\integration\modules\zebra\models\ApiCredentials;
use app\modules\integration\modules\zebra\models\UserSettings;


/**
 * Class CallController
 * @package app\modules\integration\modules\zebra\controllers
 */
class CallController extends CoreController
{
    /**
     * Check a call in zebra
     *
     * @param $id
     * @return null|\yii\web\Response
     * @throws \Exception
     */
    public function actionCheck($id)
    {
        $hm = UserHeadManager::findHeadManagerByUser();

        /** @var ApiCredentials $credentials */
        $credentials = ApiCredentials::find()->where(['user_id' => $hm->id])->one();

        if ($credentials && $credentials->auth()) {

            /** @var Call $call */
            $call = Call::findOne($id);

            if (!$call) {
                return null;
            }

            $user_settings = UserSettings::settings($call->user_id);

            if ($user_settings && $user_settings->number && $user_settings->name) {

                $result = $credentials->api->request($call->started_at, $call->ended_at + 60, $user_settings->number, $user_settings->name);

                if (isset($result['data']) && count($result['data'])) {

                    $call->record_url = $result['data'][0]['REC_LINK'] . $result['data'][0]['REC_FILE'];

                    $call->update(false, ['record_url']);

                    if ($call->record_url) {
                        return $this->redirect($call->record_url);
                    }
                } else {
                    $call->record_url = null;

                    $call->update(false, ['record_url']);
                }

            } else {
                $call->record_url = null;

                $call->update(false, ['record_url']);
            }

            return null;
        }

        return null;
    }
}
