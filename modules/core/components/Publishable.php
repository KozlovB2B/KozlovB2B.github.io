<?php
/**
 * Any object that publishable must implement this interface
 */

namespace app\modules\core\components;


interface Publishable
{
    /**
     * @const integer draft
     */
    const STATUS_DRAFT = 1;

    /**
     * @const integer published
     */
    const STATUS_PUBLISHED = 2;

    /**
     * @const integer creating
     */
    const STATUS_CREATING = 3;

    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @return boolean
     */
    public function isDraft();

    /**
     * @return boolean
     */
    public function isCreating();
}