<?php
/**
 * Возвращает тип устройства, с которого произошло посещение
 *
 * @return int 1 - Компьютер, 2 - Планшет, 3 - Мобильный
 */
function get_device_type()
{
    require_once "mobile_detect.php";
    $mobile_detect = new Mobile_Detect();
    if ($mobile_detect->isMobile()) {
        $device_type = 3;
    } elseif ($mobile_detect->isTablet()) {
        $device_type = 2;
    } else {
        $device_type = 1;
    }
    return $device_type;
}

/**
 * Возвращает тип браузера
 *
 * @return string
 */
function get_browser_language()
{
    require_once "language_detect.php";
    return getDefaultLanguage();
}

/**
 * Возвращает подключение к Базе Данных
 *
 * @return PDO объект подключения к бд
 */
function get_db()
{
    $dsn = 'mysql:host=localhost;dbname=ss';
    $user = 'ss';
    if (AFF_HITS_ENGINE_ENV === "prod") {
        $password = 'Q2qur7W9';
    } else {
        $user = 'root';
        $password = 'toortoor';
    }
    try {
        $db = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
    return $db;
}


/**
 * @param string $hash
 * @return int
 */
function get_user_id($hash)
{
    $db = get_db();
    $sth = $db->prepare('SELECT id FROM affiliate_account WHERE promo_code =?');
    $sth->execute([$hash]);
    return $sth->fetchColumn();

}

/**
 * @return int
 */
function get_link_id()
{
    if (empty($_SERVER['QUERY_STRING'])) {
        return null;
    }
    $db = get_db();
    $sth = $db->prepare('SELECT id FROM aff_promo_link WHERE query_string = ?');
    $sth->execute([$_SERVER['QUERY_STRING']]);
    return $sth->fetchColumn();
}

/**
 * @param $id
 */
function increment_link_hits_counter($id)
{
    $db = get_db();
    $db->prepare('UPDATE aff_promo_link SET hits = hits + 1 WHERE id = ?')->execute([$id]);
}

/**
 * Saves hit to db
 * @return bool
 */
function save_hit()
{
    $user_id = get_user_id($_GET['p']);

    if (!$user_id) {
        return false;
    }

    require_once "os_detect.php";

    $data = [];

    $data['created_at'] = time();
    $data['user_id'] = $user_id;
    $data['promo_code'] = $_GET['p'];
    $data['link_id'] = get_link_id();
    $data['query_string'] = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
    $data['utm_medium'] = !empty($_GET['utm_medium']) ? $_GET['utm_medium'] : null;
    $data['utm_source'] = !empty($_GET['utm_source']) ? $_GET['utm_source'] : null;
    $data['utm_campaign'] = !empty($_GET['utm_campaign']) ? $_GET['utm_campaign'] : null;
    $data['utm_content'] = !empty($_GET['utm_content']) ? $_GET['utm_content'] : null;
    $data['utm_term'] = !empty($_GET['utm_term']) ? $_GET['utm_term'] : null;
    $data['ip'] = empty($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["HTTP_X_REAL_IP"];
    $data['user_agent'] = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : null;
    $data['device_type'] = get_device_type();
    $data['ref'] = empty($_SERVER["HTTP_REFERER"]) ? '' : $_SERVER["HTTP_REFERER"];
    $data['browser_language'] = get_browser_language();
    $data['os'] = detect_os();
    $data['browser'] = detect_browser();

    try {
        $fields = [];
        $markers = [];
        $insert_data = [];
        foreach ($data as $k => $v) {
            $fields[] = $k;
            $markers[] = ":" . $k;
            $insert_data[":" . $k] = $v;
        }

        $db = get_db();
        $db->prepare("INSERT INTO aff_hit (" . implode(',', $fields) . ") VALUES (" . implode(',', $markers) . ");")->execute($insert_data);

        setcookie("aff_hit", $db->lastInsertId(), time() + (3600 * 24 * 600), '/');
        setcookie("aff_promo", $data['promo_code'], time() + (3600 * 24 * 600), '/');

        if ($data['link_id']) {
            increment_link_hits_counter($data['link_id']);
        }
    } catch (Exception $e) {
        die("При записи посещения произошла ошибка: " . $e->getMessage());
    }

    return true;
}
