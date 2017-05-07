<?php

use app\modules\core\components\Migration;
use app\modules\integration\models\EnabledList;
use app\modules\integration\modules\amo\models\AmoUser;
use app\modules\integration\modules\onlinepbx\models\ApiCredentials as OnpbxApiCredentials;
use app\modules\integration\modules\zebra\models\ApiCredentials as ZebraApiCredentials;

class m161122_110754_convert_integrations extends Migration
{
    public function up()
    {
        echo "    > Conversion..." . PHP_EOL . PHP_EOL;

        foreach (AmoUser::find()->where("user_id=head_id")->all() as $u) {
            EnabledList::enable($u->head_id, 'amo');
        }

        foreach (OnpbxApiCredentials::find()->all() as $opbx) {
            EnabledList::enable($opbx->user_id, 'onlinepbx');
        }

        foreach (ZebraApiCredentials::find()->all() as $zebra) {
            EnabledList::enable($zebra->user_id, 'zebra');
        }

        foreach (EnabledList::find()->all() as $list) {
            echo "\t" . $list->id . "\t" . $list->list . PHP_EOL . PHP_EOL;
        }
    }

    public function down()
    {
        EnabledList::deleteAll();
    }
}
