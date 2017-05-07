<?php

namespace app\modules\script\models\form;

use app\modules\script\models\ar\Script;
use Yii;
use yii\base\Model;
use app\modules\user\models\UserHeadManager;

/**
 * Class ImportForm
 *
 * Форма загрузки аватарки
 *
 * @package app\modules\user\models
 */
class EditorOptionsForm extends Model
{
    /**
     * @var Script
     */
    protected $_script;

    /**
     * @var UserHeadManager
     */
    protected $_head_manager;

    /**
     * @var string Стиль стрелок
     */
    public $arrow_style = 'Flowchart';

    /**
     * @var string Высота текста узла
     */
    public $node_content_max_height = 100;

    /**
     * @var int Сохранить по-умолчанию
     */
    public $as_default = 0;


    /**
     * @return Script
     */
    public function getScript()
    {
        return $this->_script;
    }

    public function rules()
    {
        return [
            [['arrow_style'], 'required'],
            [['as_default'], 'boolean'],
            [['arrow_style'], 'in', 'range' => array_keys(static::arrowStyles())],
            [['node_content_max_height'], 'integer', 'min' => 0, 'max' => 1000],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'arrow_style' => 'Стиль стрелок',
            'node_content_max_height' => 'Максимальная высота текста узла',
            'as_default' => 'Использовать по-умолчанию',
        ];
    }

    /**
     * @return array Список возможных позиций кнопок
     */
    public static function arrowStyles()
    {
        return [
            'Flowchart' => 'Схема',
            'Bezier' => 'Кривая Безье ',
            'Straight' => 'Прямая',
        ];
    }


    /**
     * @return string
     */
    public function extraSmallDevicesWarning()
    {
        return 'Чтобы настройки вступили в силу: нажмите кнопку сохранить и перезапустите редактор.';
    }

    /**
     * Загрузка данных формы из данных скрипта
     *
     * @param Script $script
     * @param UserHeadManager $head_manager
     */
    public function __construct(Script $script, UserHeadManager $head_manager)
    {
        $this->_script = $script;
        $this->_head_manager = $head_manager;

        if ($this->_script->editor_options) {
            $data = json_decode($this->_script->editor_options, true);
        } else {
            $data = json_decode($this->_head_manager->editor_options, true);
        }

        if (!$data) {
            $data = [];
        }

        parent::__construct($data);
    }

    /**
     * Сохранение настроек
     *
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->as_default) {
            $this->_head_manager->editor_options = json_encode($this->getAttributes());

            if (!$this->_head_manager->update(true, ['editor_options'])) {
                $this->addError('as_default', $this->_head_manager->getFirstError('editor_options'));

                return false;
            }
        }

        $this->_script->editor_options = json_encode($this->getAttributes());
        $this->_script->build = null;

        if (!$this->_script->update(true, ['editor_options', 'build'])) {
            $this->addError('as_default', $this->_script->getFirstError('editor_options'));

            return false;
        }

        return true;
    }
}
