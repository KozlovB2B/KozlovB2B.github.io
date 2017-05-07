<?php

namespace app\modules\billing\models;

use Yii;

/**
 * This is the model class for table "billing_balance".
 *
 * @property integer $id
 * @property double $balance
 * @property string $currency
 */
class Balance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'balance' => Yii::t('billing', 'Balance'),
            'currency' => Yii::t('billing', 'Currency')
        ];
    }


    /**
     * Up balance
     *
     * @param BalanceOperations $operation
     * @return bool
     */
    public static function up(BalanceOperations $operation)
    {
        return self::change($operation->balance_id, $operation->amount, "+");
    }

    /**
     * Down balance
     *
     * @param BalanceOperations $operation
     * @return bool
     */
    public static function down(BalanceOperations $operation)
    {
        return self::change($operation->balance_id, $operation->amount, "-");
    }

    /**
     * Change balance
     *
     * @param int $id
     * @param int $amount
     * @param string $operand
     * @return bool
     * @throws \yii\db\Exception
     */
    protected static function change($id, $amount, $operand)
    {
        if (!in_array($operand, ['+', '-'])) {
            return false;
        }

        if (!$amount || !is_numeric($amount)) {
            return false;
        }

        if (!$id || !is_numeric($id)) {
            return false;
        }

        $query = self::getDb()->createCommand('UPDATE ' . Balance::tableName() . ' SET balance = balance ' . $operand . ' :amount WHERE id = :id', [':amount' => $amount, ':id' => $id]);

        $changed = $query->execute();

        if (!$changed) {
            \Yii::error('Cant change balance! Query: ' . $query->getRawSql(), __METHOD__);
        }

        return !!$changed;
    }

    /**
     * @var Balance|null
     */
    protected static $_current_user_instance;

    /**
     * @return Balance|bool|null|static
     */
    public static function currentUserBalance()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            self::$_current_user_instance = false;
        }

        if(self::$_current_user_instance === null){
            self::$_current_user_instance = Balance::findOne(Yii::$app->getUser()->getId());
        }

        return self::$_current_user_instance;
    }
}
