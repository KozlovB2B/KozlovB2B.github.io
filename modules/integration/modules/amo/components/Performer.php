<?php

namespace app\modules\integration\modules\amo\components;

use app\modules\integration\components\IPerformer;
use app\modules\integration\modules\amo\models\AmoUser;
use app\modules\script\models\Call;
use yii\base\Component;
use Yii;

/**
 * Class Detector
 *
 * Detects AMO integration possibility
 *
 * @package app\modules\integration\modules\amo\components
 */
class Performer extends Component implements IPerformer
{
    /**
     * @inheritdoc
     */
    public function perform(Call $call)
    {
        if ($call->perform_page) {
            $regexp = '/^https:\/\/(.*)\.amocrm\.ru.*$/';

            preg_match($regexp, $call->perform_page, $matches);

            // Checking if its contact page
            $contact_regexp = '/^https:\/\/(.*)\.amocrm\.ru\/contacts\/detail\/(\d+).*$/';

            preg_match($contact_regexp, $call->perform_page, $contact_matches);

            if (isset($matches[1]) && isset($contact_matches[2])) {

                $subdomain = $matches[1];

                /** @var AmoUser $credentials */
                $credentials = AmoUser::find()->where(['subdomain' => $subdomain, 'user_id' => $call->user_id])->one();

                if (!$credentials) {
                    $credentials = AmoUser::find()->where(['subdomain' => $subdomain, 'user_id' => $call->account_id])->one();
                }

                if ($credentials) {
                    $api = new AmoApi($credentials);

                    $api->auth();

                    AmoNote::create($api, $contact_matches[2], $call);
                }
            }
        }
    }
}