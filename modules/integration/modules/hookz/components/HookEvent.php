<?php

namespace app\modules\integration\modules\hookz\components;

use yii\base\Component;
use Yii;

class HookEvent extends Component
{
    /**
     * @const integer При начале звонка
     */
    const ON_CALL_START = 1;

    /**
     * @const integer При завершении звонка
     */
    const ON_CALL_END = 2;

    /**
     * @const integer При отправке отчета оператором
     */
    const ON_REPORT = 3;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            static::ON_CALL_START => 'При начале звонка',
            static::ON_CALL_END => 'При завершении звонка',
            static::ON_REPORT => 'При отправке отчета',
        ];
    }

    /**
     * @return array
     */
    public static function descriptions()
    {
        return [
            static::ON_CALL_START => [
                'name' => 'При начале звонка',
                'description' => 'Происходит, когда оператор нажимает кнопку &laquo;Взяли трубку&raquo; - показывается первый узел и стартует таймер.',
            ],
            static::ON_CALL_END =>[
                'name' => 'При завершении звонка',
                'description' => 'Происходит, когда оператор нажимает кнопку &laquo;Завершить&raquo; или закрывает прогонщик или страницу браузера без завершения звонка.',
            ],
            static::ON_REPORT => [
                'name' => 'При отправке отчета',
                'description' => 'Происходит, когда оператор заполняет форму отчета после завершения звонка и нажимает кнопку - &laquo;Отправить&raquo;',
            ],
        ];
    }

}