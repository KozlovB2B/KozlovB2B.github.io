<?php

namespace app\modules\integration\modules\zebra\components;

use app\modules\integration\components\IPerformer;
use app\modules\integration\modules\zebra\models\ApiCredentials;
use app\modules\script\models\Call;
use yii\base\Component;
use Yii;

/**
 * Class Detector
 *
 * Detects Zebra integration possibility
 *
 * @package app\modules\integration\modules\zebra\components
 */
class Performer extends Component implements IPerformer
{
    /**
     * @inheritdoc
     */
    public function perform(Call $call)
    {
        if ($call->record_url) {
            return false;
        }

        /** @var ApiCredentials $credentials */
        $credentials = ApiCredentials::find()->where(['user_id' => $call->account_id])->one();

        if ($credentials) {

            $call->record_url = '/integration/zebra/call/check?id=' . $call->id;

            return $call->update(false, ['record_url']);
        }

        return false;
    }
}