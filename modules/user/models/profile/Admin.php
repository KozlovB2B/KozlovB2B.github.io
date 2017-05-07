<?php

namespace app\modules\user\models\profile;

/**
 * Class Admin
 *
 * Класс профиля админа
 *
 * @package app\modules\user\models\profile
 */
class Admin extends TeamMemberProfile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_admin';
    }

    /**
     * @inheritdoc
     */
    public static function redirect()
    {
        return "/overview";
    }
}
