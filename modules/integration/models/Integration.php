<?php
namespace app\modules\integration\models;

use Yii;
use yii\base\Component;
use yii\helpers\Html;

/**
 * Class Integration
 *
 * All the integrations
 */
class Integration extends Component
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var callable
     */
    public $moduleClass;

    /**
     * @var \yii\web\AssetBundle
     */
    public $logoAsset;

    /**
     * @var string
     */
    public $name;

    public static function getList()
    {
        return [
            Yii::createObject([
                'class' => Integration::className(),
                'id' => 'recorder',
                'moduleClass' => 'app\modules\integration\modules\recorder\Module',
                'logoAsset' => 'app\modules\integration\modules\recorder\components\LogoAssetBundle',
                'name' => Yii::t("integration", 'Call recording'),
            ]),
            Yii::createObject([
                'class' => Integration::className(),
                'id' => 'apiv2',
                'moduleClass' => 'app\modules\integration\modules\apiv2\Module',
                'logoAsset' => 'app\modules\integration\modules\apiv2\components\LogoAssetBundle',
                'name' => Yii::t("integration", 'API'),
            ]),
            Yii::createObject([
                'class' => Integration::className(),
                'id' => 'widget',
                'moduleClass' => 'app\modules\integration\modules\widget\Module',
                'logoAsset' => 'app\modules\integration\modules\widget\components\LogoAssetBundle',
                'name' => Html::tag('span', Yii::t("integration", 'Widget'), ['style' => 'font-size: 65%']),
            ]),
            Yii::createObject([
                'class' => Integration::className(),
                'id' => 'hookz',
                'moduleClass' => 'app\modules\integration\modules\hookz\Module',
                'logoAsset' => 'app\modules\integration\modules\hookz\components\LogoAssetBundle',
                'name' => Yii::t("integration", 'WebHooks'),
            ]),
            Yii::createObject([
                'class' => Integration::className(),
                'id' => 'amo',
                'moduleClass' => 'app\modules\integration\modules\amo\Module',
                'logoAsset' => 'app\modules\integration\modules\amo\components\LogoAssetBundle',
                'name' => Yii::t("integration", 'Amo CRM'),
            ]),
            Yii::createObject([
                'class' => Integration::className(),
                'id' => 'onlinepbx',
                'moduleClass' => 'app\modules\integration\modules\onlinepbx\Module',
                'logoAsset' => 'app\modules\integration\modules\onlinepbx\components\LogoAssetBundle',
                'name' => Yii::t("integration", 'Online PBX'),
            ]),
            Yii::createObject([
                'class' => Integration::className(),
                'id' => 'zebra',
                'moduleClass' => 'app\modules\integration\modules\zebra\Module',
                'logoAsset' => 'app\modules\integration\modules\zebra\components\LogoAssetBundle',
                'name' => Yii::t("integration", 'Zebra Telecom'),
            ])
        ];
    }

}