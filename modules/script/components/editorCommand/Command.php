<?php

namespace app\modules\script\components\editorCommand;

use app\modules\script\models\ar\Group;
use app\modules\script\models\ar\GroupVariant;
use yii\base\Model;
use yii\db\ActiveRecord;
use app\modules\script\models\ar\Node;
use app\modules\script\models\ar\Script;
use app\modules\script\models\ar\Variant;
use app\modules\script\models\ar\NodeClone;
use Yii;

/**
 * Class Command
 * @package app\modules\script\components\editorCommand
 */
class Command extends Model
{
    /**
     * @var ActiveRecord
     */
    protected $_model;

    /**
     * @var string Идентификатор действия - генерируется на клиентской стороне
     */
    public $id;

    /**
     * @var string Короткое название класса модели, которую будем изменять
     */
    public $model_class;

    /**
     * @var string
     */
    public $model_id;

    /**
     * @var array Данные, которые будет использовать rollback функция
     */
    public $r = [];

    /**
     * @var array Данные, которые будет использовать perform функция
     */
    public $p = [];

    /**
     * @return array
     */
    protected static function map()
    {
        return [
            'Node' => Node::className(),
            'Variant' => Variant::className(),
            'Script' => Script::className(),
            'Group' => Group::className(),
            'NodeClone' => NodeClone::className(),
            'GroupVariant' => GroupVariant::className()
        ];
    }


    /**
     * @return ActiveRecord
     */
    public function getModel()
    {
        if ($this->_model === null) {

            /** @var ActiveRecord $model_class_name */
            $model_class_name = static::map()[$this->model_class];
            $this->_model = $model_class_name::findOne($this->model_id);

            if (!$this->_model) {
                $this->_model = Yii::createObject([
                    'class' => $model_class_name,
                    'id' => $this->model_id
                ]);
            }
        }

        return $this->_model;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'model_class', 'model_id', 'r', 'p'], 'required'],
            [['r'], 'checkKeys']
        ];
    }

    /**
     * Проверяет обязательные атрибуты r и p чтобы они были массивами и ключи этих массивов были одинаковые
     *
     * @return bool
     */
    public function checkKeys()
    {
        if (!is_array($this->r)) {
            $this->addError('r', 'Атрибут r должен быть массивом!');
            return false;
        }

        if (!is_array($this->p)) {
            $this->addError('r', 'Атрибут r должен быть массивом!');
            return false;
        }


        $p = array_keys($this->p);
        $r = array_keys($this->r);

        sort($p);
        sort($r);

        if ($p !== $r) {
            // p - call_stage_id, content, deleted_at, groups, id, is_goal, left, normal_ending, number, script_id, top
            // r - call_stage_id, content, deleted_at, id, is_goal, left, normal_ending, number, script_id, top
            $this->addError('r', 'Атрибуты r и p должны содержать одинаковый набор ключей: p - ' . implode(', ', $p) . ' r - ' . implode(', ', $r));

            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function perform()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->getModel()->setAttributes($this->p, false);

        return $this->getModel()->save();
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->getModel()->setAttributes($this->r, false);

        return $this->getModel()->save();
    }

    /**
     * @return string
     */
    public function getErrorsAsString()
    {
        $result = implode(',', $this->getFirstErrors());
        $result .= ' ' . implode(',', $this->getModel()->getFirstErrors());
        return $result;
    }
}