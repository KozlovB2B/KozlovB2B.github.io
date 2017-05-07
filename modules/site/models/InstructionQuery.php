<?php

namespace app\modules\site\models;

/**
 * This is the ActiveQuery class for [[Instruction]].
 *
 * @see Instruction
 */
class InstructionQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[deleted_at]] IS NULL');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Instruction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Instruction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}