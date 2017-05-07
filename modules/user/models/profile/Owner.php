<?php

namespace app\modules\user\models\profile;

/**
 * Class Owner
 *
 * Класс профиля владельца
 *
 * @package app\modules\user\models\profile
 */
class Owner extends TeamMemberProfile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_owner';
    }

    /**
     * @inheritdoc
     */
    public static function redirect()
    {
        return "/overview";
    }

    public function getRole()
    {
        return 'god';
    }
}
