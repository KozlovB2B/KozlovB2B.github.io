<?php

namespace app\modules\integration\components;

/**
 * Interface IDetector
 *
 * @package app\modules\integration\components
 */
interface IDetector
{

    /**
     * Detects url and decide can be integration be for parent module and current user
     *
     * @param string $url URL of external resource for which integration possibility will be detected
     * @return boolean Need integration for given resource or not
     */
    public function detect($url);

    /**
     * A message for user to say that integration is possible with link to form UI
     *
     * @return string
     */
    public function getWelcomeMessage();
}