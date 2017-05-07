<?php
// Do not write hit if it is watch.js request
if (!empty($_SERVER['HTTP_REFERER']) && !empty($_SERVER['QUERY_STRING'])) {
    $ref_data = explode('?', $_SERVER['HTTP_REFERER']);
    if (isset($ref_data[1]) && $ref_data[1] == $_SERVER['QUERY_STRING']) {
        return false;
    }
}

defined('AFF_HITS_ENGINE_ENV') || define('AFF_HITS_ENGINE_ENV', (strpos($_SERVER['SERVER_ADDR'], '192.168') !== false ? 'dev' : 'prod'));
defined('AFF_HITS_ENGINE_DEBUG') || define('AFF_HITS_ENGINE_DEBUG', !empty($_GET["debug"]) ? true : false);

if (!isset($_GET['p'])) {
    return false;
}

require_once "functions.php";

save_hit();