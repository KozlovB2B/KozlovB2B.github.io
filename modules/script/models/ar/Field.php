<?php

namespace app\modules\script\models\ar;

use Yii;
use yii\db\ActiveRecord;
use app\modules\user\models\User;
use app\modules\script\models\query\FieldQuery;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "field".
 *
 * @property integer $id
 * @property string $code
 * @property integer $account_id
 * @property string $type
 * @property string $name
 * @property string $type_data
 *
 * @property User $account
 */
class Field extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['code', function () {
                $this->code = str_replace(' ', '_', $this->code);
            }],
            [['name', 'code', 'type_data'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['account_id', 'type', 'name', 'code'], 'required'],
            [['account_id'], 'integer'],
            [['code'], 'string', 'max' => 64],
            [['type_data'], 'string', 'max' => 1024],
            [['type'], 'requiredIfValidatorIn'],
            [['type', 'name'], 'string', 'max' => 32],
            [['code'], 'unique', 'targetAttribute' => ['account_id', 'code'], 'comboNotUnique' => 'У вас уже есть поле с таким кодом'],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['account_id' => 'id']],
        ];
    }

    public function displayHtml($value = null)
    {
        /**
         *  <strong class="field">
         * <span class="field-label">Имя</span>
         * <br/>
         * <span class="field-value">Сергей</span>
         * </strong>
         */

        $display_value = '_';

        if ($this->type == 'boolean') {
            $display_value = $value ? 'Да' : 'Нет';
        } else if ($value) {
            $display_value = $value;
        }

        $inner_html = Html::tag('strong', $this->name, ['class' => 'field-label']) . '<br/>' . Html::tag('strong', $display_value, ['class' => 'field-value']);

        return Html::tag('strong', $inner_html, ['class' => 'field', 'data-field' => base64_encode(Json::encode($this->getAttributes()))]);
    }

    /**
     * @return string
     */
    public function editorHtml()
    {
//        return Html::tag('strong', $this->name . Html::tag('strong', base64_encode(Json::encode($this->getAttributes(['code', 'name', 'type', 'type_data']))), ['class' => 'field-data']), ['class' => 'field', 'data-field' => base64_encode(Json::encode($this->getAttributes(['code', 'name', 'type', 'type_data'])))]);
        return Html::tag('strong', $this->name, ['class' => 'field', 'data-field' => base64_encode(Json::encode($this->getAttributes(['code', 'name', 'type', 'type_data'])))]);
    }

    /**
     * @return bool
     */
    public function requiredIfValidatorIn()
    {
        if ($this->type == 'in' && !$this->type_data) {
            $this->addError('type_data', Yii::t('script', 'Data is required'));

            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        Yii::$app->getModule('script');
        return [
            'id' => Yii::t('script', 'ID'),
            'type_data' => Yii::t('script', 'Data'),
            'code' => Yii::t('script', 'Code'),
            'account_id' => Yii::t('script', 'Account ID'),
            'type' => Yii::t('script', 'Field type'),
            'name' => Yii::t('script', 'Name'),
        ];
    }


    /**
     * @inheritdoc
     */
    public static function typesList()
    {
        return [
            'string' => Yii::t('script', 'String'),
            'number' => Yii::t('script', 'Number'),
            'boolean' => Yii::t('script', 'Checkbox'),
            'in' => Yii::t('script', 'List'),
            'date' => Yii::t('script', 'Date'),
            'time' => Yii::t('script', 'Time')
        ];
    }

    public function getTypeName()
    {
        if (!$this->type) {
            return null;
        }

        return static::typesList()[$this->type];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(User::className(), ['id' => 'account_id']);
    }

    /**
     * @inheritdoc
     * @return FieldQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FieldQuery(get_called_class());
    }
}
