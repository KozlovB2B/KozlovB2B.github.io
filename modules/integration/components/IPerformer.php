<?php

namespace app\modules\integration\components;
use app\modules\script\models\Call;

/**
 * Interface IPerformer
 *
 * @package app\modules\integration\components
 */
interface IPerformer
{

    /**
     * Performs integration action after Call completed
     *
     * @param Call $call A Call to perform action
     */
    public function perform(Call $call);
}