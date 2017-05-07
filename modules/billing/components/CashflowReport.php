<?php
namespace app\modules\billing\components;

use app\modules\billing\models\BalanceOperations;
use app\modules\billing\models\ServiceUsageLog;
use yii\data\ActiveDataProvider;
use app\modules\core\components\ExcelExport;
use juffin_halli\dataProviderIterator\DataProviderIterator;
use Yii;
use DateTime;

/**
 * This is the model class for table "balance_operations".
 *
 * @property integer $id
 * @property integer $balance_id
 * @property integer $is_accrual
 * @property integer $type_id
 * @property integer $amount
 * @property string $currency
 * @property string $comment
 * @property integer $created_at
 */
class CashflowReport extends ServiceUsageLog
{

    protected $_month_prefix = 'm_';

    /** @var string */
    public $from;
    public $to;

    /** @var int */
    public $total_withdraw;

    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['from', 'to'], 'safe'],
        ];
    }

    public function getMinMonth()
    {
        return date('m.Y', strtotime(self::getDb()->createCommand('SELECT MIN(month) FROM ' . self::tableName())->queryScalar()));
    }

    public function getMaxMonth()
    {
        return date('m.Y', strtotime(self::getDb()->createCommand('SELECT MAX(month) FROM ' . self::tableName())->queryScalar()));
    }


    public function asExcel()
    {
        $excel = new ExcelExport();
        Yii::$app->getDb()->createCommand("SET NAMES cp1251")->execute();
        $data = new DataProviderIterator($this->search(), 1000);

        $excel->totalCol = 7 + count($this->_months);

        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Пользователь'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Пользователь'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Телефон'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Текущий баланс'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'ИНН'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Название организации'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Всего за период'));

        foreach ($this->getMonths() as $month => $date) {
            $excel->InsertText(Yii::$app->getFormatter()->asDate(strtotime($date), 'MM.Y'));
        }


        $excel->GoNewLine();

        /** @var CashflowReport $model */
        foreach ($data as $model) {
            $excel->InsertText($model->account_id);
            $excel->InsertText($model->user->username);
            $excel->InsertText($model->userHeadManager->phone);
            $excel->InsertText($model->balance->balance);
            $excel->InsertText($model->bankProps ? $model->bankProps->inn : null);
            $excel->InsertText($model->bankProps ? $model->bankProps->company_name : null);

            $excel->InsertNumber((int)$model->total_withdraw);

            foreach (array_keys($this->getMonths()) as $month) {
                $excel->InsertNumber((int)($model->$month == 0 ? null : $model->$month));
            }

            $excel->GoNewLine();
        }

        $excel->SaveFile('cashflow_by_months_' . $this->from . '_' . $this->to);
    }

    /**
     * @var array Current dynamic months
     */
    protected $_months = [];

    /**
     * @return array Current months attributes
     */
    public function getMonths()
    {
        return $this->_months;
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->_months[$name])) {
            return $this->_months[$name];
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (substr($name, 0, 2) == $this->_month_prefix) {
            $this->_months[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (substr($name, 0, 2) == $this->_month_prefix) {
            return true;
        }

        return parent::canSetProperty($name, $checkVars, $checkBehaviors);
    }


    /**
     * Определяет набор свойств-месяцев
     */
    protected function defineQueryMonths()
    {
        $this->_months = [];

        $date = new DateTime('01.' . $this->from);
        $end = new DateTime('01.' . $this->to);

        while ($date->getTimestamp() <= $end->getTimestamp()) {
            $attr = $this->_month_prefix . $date->format('m_Y');
            $this->_months[$attr] = $date->format('Y-m-01');
            $date->modify('first day of next month');
//            $this->_months[$attr][] = $date->getTimestamp();
        }
    }


    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = self::find();
        $query->groupBy(['ServiceUsageLog.account_id']);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'account_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load(Yii::$app->request->get());

        if (!$this->from) {
            $this->from = $this->getMinMonth();
            $this->to = $this->getMaxMonth();
        }

        $this->defineQueryMonths();

        $query->select([
            'ServiceUsageLog.account_id',
            'sum(amount) as total_withdraw',
        ]);

        foreach ($this->_months as $attr_name => $month) {
            $query->select[] = 'sum(if(ServiceUsageLog.month = "' . $month . '" , ServiceUsageLog.amount, 0)) as ' . $attr_name;
        }

        $from = new DateTime('01.' . $this->from);
        $to = new DateTime('01.' . $this->to);
//        $to->modify('first day of next month');
        $query->andWhere(['between', 'ServiceUsageLog.day', $from->format('Y-m-d'), $to->format('Y-m-t')]);

        return $dataProvider;
    }

}