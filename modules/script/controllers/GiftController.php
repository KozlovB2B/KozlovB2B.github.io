<?php

namespace app\modules\script\controllers;

use app\modules\core\components\CoreController;
use app\modules\script\components\Gift;
use app\modules\script\models\ar\Script;
use app\modules\user\models\profile\Head;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\base\Exception;
use yii\web\Response;

/**
 * ScriptController implements the CRUD actions for Script model.
 *
 * @method Script findModel($id, $class = null) see [[FindModelTrait::findModel()]] for more info
 */
class GiftController extends CoreController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ],
        ];
    }

    /**
     * Take our gift
     */
    public function actionAccept()
    {
        $this->checkAccess("script___gift__accept");

        $head = Head::current();

        if ($head->info->gift_accepted === null) {
            Gift::accept($head->id);

            return ['status' => 'ok', 'message' => 'Спасибо, что приняли наш подарок!'];
        } else {
            throw new Exception($head->info->gift_accepted ? 'Вы уже приняли наш подарок!' : 'Вы уже отказались от подарка!');
        }
    }

    /**
     * Take our gift
     */
    public function actionDecline()
    {
        $this->checkAccess("script___script__create");

        $head = Head::current();

        if ($head->info->gift_accepted === null) {
            UserHeadManager::declineGift($head->id);

            return ['status' => 'ok', 'message' => 'Жаль, что наш подарок вам не пригодился!'];
        } else {
            throw new Exception($head->info->gift_accepted ? 'Вы уже приняли наш подарок!' : 'Вы уже отказались от подарка!');
        }
    }
}
