<?php

namespace app\modules\user\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends ActiveQuery
{
    /**
     * Находит пользователя по его ID
     *
     * @param integer $id
     * @return $this
     */
    public function byId($id)
    {
        return $this->andWhere('[[id]]=:id', [':id' => $id]);
    }

    /**
     * Находит пользователя по его имени пользователя
     *
     * @param string $username
     * @return $this
     */
    public function byUsername($username)
    {
        return $this->andWhere('[[username]]=:username', [':username' => trim($username)]);
    }

    /**
     * Находит пользователя по его почте
     *
     * @param string $email
     * @return $this
     */
    public function byEmail($email)
    {
        return $this->andWhere('[[email]]=:email', [':email' => trim($email)]);
    }

    /**
     * Определяет что передано в качестве аргумента
     * и выдает файндер метод по имени пользователя или почте
     *
     * @param string $search Логин или почта
     * @return $this
     */
    public function byUsernameOrEmail($search)
    {
        if (filter_var($search, FILTER_VALIDATE_EMAIL)) {
            return $this->byEmail($search);
        }

        return $this->byUsername($search);
    }

    /**
     * Определяет что передано в качестве аргумента
     * и выдает файндер метод ID, по имени пользователя или почте
     *
     * @param string $search Логин или почта или ID
     * @return $this
     */
    public function byIdUsernameOrEmail($search)
    {
        if (is_numeric($search)) {
            return $this->byId($search);
        }

        return $this->byUsernameOrEmail($search);
    }

    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
