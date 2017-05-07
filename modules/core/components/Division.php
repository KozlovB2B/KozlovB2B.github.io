<?php
namespace app\modules\core\components;

/**
 * Class Division this application divisions
 *
 * @package app\modules\core\components
 */
class Division
{
    /**
     * @const string Division russian speakers
     */
    const DIVISION_RU = 'ru-RU';

    /**
     * @const string Division english speakers US
     */
    const DIVISION_EN_US = 'en-US';

    /**
     * @return array Current active divisions
     */
    public static function active()
    {
        return [
            static::DIVISION_EN_US => static::DIVISION_EN_US,
            static::DIVISION_RU => static::DIVISION_RU
        ];
    }
}