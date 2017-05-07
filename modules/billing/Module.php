<?php

namespace app\modules\billing;

use app\modules\billing\models\Payment;
use Yii;

class Module extends \yii\base\Module
{
    /**
     * @var array Current active payment services
     */
    public $payment_services = [];

    /**
     * @return bool
     */
    public function hasPaymentServices(){
        return !!$this->payment_services;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();

        $components = [];

        foreach (Payment::paymentServices() as $c => $conf) {
            if (in_array(Yii::$app->params['division'], $conf['divisions'])) {
                $conf['currency'] = Yii::$app->params['currency'];
                $components[$c] = $conf;
                $this->payment_services[$c] = $conf['name'];
            }
        }

        $this->setComponents($components);
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['billing'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/billing/messages',
            'fileMap' => [
                'billing' => 'billing.php'
            ],
        ];
    }
}