<?php

namespace app\modules\script\models\form;

use app\modules\script\models\ar\Script;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\script\components\V2Importer;
use app\modules\script\components\V1Importer;

/**
 * Class ImportForm
 *
 * Форма загрузки аватарки
 *
 * @package app\modules\user\models
 */
class PerformerOptionsForm extends Model
{
    /**
     * @var Script
     */
    protected $_script;

    /**
     * @var string Расположение ответов
     */
    public $node_font_size = 'medium';

    /**
     * @var string Расположение ответов
     */
    public $variants_position = 'bottom';

    /**
     * @var string Расположение групповых
     */
    public $group_variants_position = 'right';

    /**
     * @var string Стиль ответов
     */
    public $variants_style = 'buttons';

    /**
     * @var string Стиль ответов
     */
    public $group_variants_style = 'links';

    /**
     * @var string Размер шрифта вариантов
     */
    public $variants_size = 'medium';

    /**
     * @var string Размер шрифта групповых вариантов
     */
    public $group_variants_size = 'medium';

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
            [['variants_position', 'variants_style', 'group_variants_style', 'variants_size', 'group_variants_size'], 'required'],
            [['variants_position','group_variants_position'], 'in', 'range' => array_keys(static::variantsPositions())],
            [['node_font_size'], 'in', 'range' => array_keys(static::nodeFontSizes())],
            [['variants_style', 'group_variants_style'], 'in', 'range' => array_keys(static::variantsStyles())],
            [['variants_size', 'group_variants_size'], 'in', 'range' => array_keys(static::variantsSizes())],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'node_font_size' => 'Размер шрифта узла',
            'variants_position' => 'Расположение',
            'group_variants_position' => 'Расположение',
            'variants_style' => 'Стиль ответов',
            'group_variants_style' => 'Стиль групповых ответов',
            'variants_size' => 'Размер кнопок (ссылок)',
            'group_variants_size' => 'Размер кнопок (ссылок)',
        ];
    }

    /**
     * @return array Список возможных позиций кнопок
     */
    public static function variantsPositions()
    {
        return [
            'left' => 'Слева',
            'right' => 'Справа',
            'bottom' => 'Снизу',
        ];
    }


    /**
     * @return array Список возможных позиций кнопок
     */
    public static function variantsStyles()
    {
        return [
            'buttons' => 'Кнопки',
            'links' => 'Ссылки'
        ];
    }

    /**
     * @return array Размер кнопок (размер шрифта ссылок)
     */
    public static function nodeFontSizes()
    {
        return [
            'large' => 'Большой',
            'medium' => 'Нормальный',
            'small' => 'Маленький'
        ];
    }

    /**
     * @return array Размер кнопок (размер шрифта ссылок)
     */
    public static function variantsSizes()
    {
        return [
            'large' => 'Большой',
            'medium' => 'Нормальный',
            'small' => 'Небольшой',
            'extra-small' => 'Минимальный'
        ];
    }

    /**
     * @return string
     */
    public function extraSmallDevicesWarning()
    {
        return 'На устройствах с шириной экрана менее 768 пикселей варианты ответов и текст будут иметь минимальный размер шрифта (размер кнопок) независимо от установленных настроек.';
    }

    /**
     * Загрузка данных формы из данных скрипта
     *
     * @param Script $script
     */
    public function __construct(Script $script)
    {
        $this->_script = $script;

        $data = json_decode($this->_script->performer_options, true);

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

        $this->_script->performer_options = json_encode($this->getAttributes());
        $this->_script->build = null;

        if (!$this->_script->update(true, ['performer_options', 'build'])) {
            $this->addError('variants_position', $this->_script->getFirstError('performer_options'));

            return false;
        }

        return true;
    }
}
