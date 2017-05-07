<?php
namespace app\modules\aff\models;

use yii\data\ActiveDataProvider;
use app\modules\core\components\ExcelExport;
use juffin_halli\dataProviderIterator\DataProviderIterator;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "aff_hit".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $user_id
 * @property string $promo_code
 * @property integer $link_id
 * @property string $query_string
 * @property string $utm_medium
 * @property string $utm_source
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property string $ip
 * @property string $user_agent
 * @property string $browser_language
 * @property integer $device_type
 * @property string $os
 * @property string $browser
 * @property string $ref
 * @property integer $has_registrations
 * @property integer $bills
 * @property integer $bills_paid
 * @property integer $total_earned
 *
 * @property PromoLink $link
 */
class AdEffectReport extends ActiveRecord
{
    /** @var string */
    public $created_at;

    /** @var string */
    public $source;

    /** @var integer */
    public $total_count;
    public $registrations;
    public $bills_total;
    public $bills_paid_total;
    public $earned;

    /** @var boolean */
    public $registered;
    public $paid;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'aff_hit';
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['registered', 'paid', 'utm_medium', 'utm_source', 'created_at', 'source', 'total_count', 'registrations', 'bills_total', 'bills_paid_total', 'earned'], 'safe'],
            'fieldsBoolean' => [['registered', 'paid'], 'boolean']
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('aff', 'Period'),
            'total_count' => Yii::t('aff', 'Hits total'),
            'registrations' => Yii::t('aff', 'Registrations'),
            'bills_total' => Yii::t('aff', 'Bills'),
            'bills_paid_total' => Yii::t('aff', 'Bills paid'),
            'earned' => Yii::t('aff', 'Earned'),
            'utm_medium' => Yii::t('aff', 'Traffic (utm_medium)'),
            'utm_source' => Yii::t('aff', 'Source (utm_source)'),
            'paid' => Yii::t('aff', 'Paid'),
            'registered' => Yii::t('aff', 'Registered'),

        ];
    }

    public function asExcel()
    {
        $filename = 'ad_effect_report_' . $this->created_at;

        $excel = new ExcelExport();
        Yii::$app->getDb()->createCommand("SET NAMES cp1251")->execute();
        $data = new DataProviderIterator($this->dataProvider(), 1000);

        $excel->totalCol = 7;
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('utm_source')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('utm_source')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('total_count')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('earned')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('registrations')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('bills_total')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', $this->getAttributeLabel('bills_paid_total')));


        $excel->GoNewLine();

        /** @var AdEffectReport $model */
        foreach ($data as $model) {
            $excel->InsertText($model->utm_medium);
            $excel->InsertText($model->utm_source);
            $excel->InsertText($model->total_count);
            $excel->InsertText($model->earned);
            $excel->InsertText($model->registrations);
            $excel->InsertText($model->bills_total);
            $excel->InsertText($model->bills_paid_total);
            $excel->GoNewLine();
        }

        $excel->SaveFile($filename);
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $query = $this->find();

        $query->groupBy(['utm_medium', 'utm_source']);

        $query->select([
            'utm_medium',
            'utm_source',
            'count(*) as total_count',
            'sum(total_earned) as earned',
            'sum(has_registrations) as registrations',
            'sum(bills) as bills_total',
            'sum(bills_paid) as bills_paid_total'
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $query->where(['user_id' => \Yii::$app->getUser()->getId()]);

        if (!($this->load(\Yii::$app->request->get()) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['utm_medium' => $this->utm_medium]);

        $query->andFilterWhere(['utm_source' => $this->utm_source]);

        if ($this->paid !== '') {
            $this->registered = 1;

            if ($this->paid) {
                $query->andWhere('total_earned > 0');
            } else {
                $query->andWhere('total_earned = 0 OR total_earned IS NULL');
            }
        }

        if ($this->registered !== '') {
            if ($this->registered) {
                $query->andWhere('has_registrations > 0');
            } else {
                $query->andWhere('has_registrations = 0 OR has_registrations IS NULL');
            }
        }

        if ($this->created_at) {
            list($from, $to) = explode(' - ', $this->created_at);
            $to = $to . ' 23:59:59';
            $from = $from . ' 00:00:00';
            $query->andWhere(['between', 'created_at', strtotime($from), strtotime($to)]);
        }

        return $dataProvider;
    }

}