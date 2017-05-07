<?php
namespace app\modules\script\components\phantomjs;

use Yii;

/**
 * Class PhantomJs - phantomjs execution wrapper
 *
 * Install guide - https://gist.github.com/julionc/7476620
 * todo удалить phantomjs после полного отказа от v1
 * @package app\modules\script\components
 */
class PhantomJs
{
    /**
     * Make screen of page
     *
     * @param $capture_url
     * @param $filename
     */
    public static function screenCapture($capture_url, $filename)
    {
        exec('phantomjs ' . __DIR__ . '/capture.js ' . $capture_url . ' ' . $filename, $output);
    }
}