<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 19.02.16
 * Time: 22:55
 */

namespace app\modules\blog\components;


class Share extends \bigpaulie\social\share\Share
{
    protected $networks = [
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u={url}',
        'google-plus' => 'https://plus.google.com/share?url={url}',
        'twitter' => 'https://twitter.com/home?status={url}',
        'vk' => 'http://vk.com/share.php?url={url}',
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url={url}',
    ];
}