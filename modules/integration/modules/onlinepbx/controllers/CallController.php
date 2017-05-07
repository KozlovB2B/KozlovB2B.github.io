<?php

namespace app\modules\integration\modules\onlinepbx\controllers;

use app\modules\core\components\CoreController;
use app\modules\script\models\Call;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\integration\modules\onlinepbx\models\ApiCredentials;
use app\modules\integration\modules\onlinepbx\models\UserSettings;


/**
 * Class CallController
 * @package app\modules\integration\modules\onlinepbx\controllers
 */
class CallController extends CoreController
{
    /**
     * Listen a call
     *
     * @param $uid
     * @return null|\yii\web\Response
     */
    public function actionListen($uid)
    {
        /** @var ApiCredentials $credentials */
        $hm = UserHeadManager::findHeadManagerByUser();
        $credentials = ApiCredentials::find()->where(['user_id' => $hm->id])->one();

        if ($credentials && $credentials->auth()) {

            $result = $credentials->api->request('history/search.json', [
                'uuid' => $uid,
                'download' => true
            ]);

            if (!empty($result['data'])) {
                return $this->redirect($result['data']);
            }
        }

        return null;
    }

    /**
     * Check a call in online pbx
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

            $search = [
                'date_from' => gmdate('d M Y H:i:s T', $call->started_at - 120),
                'date_to' => gmdate('d M Y H:i:s T', $call->ended_at + 120),
            ];

            if ($user_settings) {
                $search['number'] = $user_settings->number;
            }

            $result = $credentials->api->request('history/search.json', $search);

            if (isset($result['data']) && count($result['data']) && !empty($result['data'][count($result['data']) - 1]['uuid'])) {
                $call->record_url = '/integration/onlinepbx/call/listen?uid=' . $result['data'][count($result['data']) - 1]['uuid'];
                $call->update(false, ['record_url']);

                return $this->redirect($call->record_url);
            }

            return null;
        }

        return null;
    }
}
