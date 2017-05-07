<?php
namespace app\modules\integration\modules\zebra\components;

use app\modules\integration\modules\zebra\models\ApiCredentials;
use yii\base\Exception;

class ZebraApi
{
    private $errors;

    /**
     * @var ApiCredentials
     */
    public $credentials;

    /**
     * @var string Working key for requests
     */
    public $auth_token;

    /**
     * @var string Working key id for requests
     */
    public $account_id;

    public function __construct(ApiCredentials $credentials)
    {
        $this->credentials = $credentials;
        $this->getWorkingKey();
    }

    /**
     * Auth in Zebra api - gets working key
     * Для авторизации необходимо сделать PUT-запрос к http://api.zebratelecom.ru/v1/kazoos/user_auth со следующим содержимым:
     * {
     * "data": {
     * "login": "login",
     * "password": "password",
     * "realm": "00000.ztpbx.ru"
     * }
     * }
     * Ответ от сервера:
     * {
     * "data": {
     * "error_code": "0",
     * "error_message": "Success",
     * "status": "success",
     * "auth_token": "нужный_вам_auth_token",
     * "account_id": " example ",
     * "account_realm": " example ",
     * "owner_id": " example ",
     * "owner_role": " example "
     * },
     * "error_code": "0",
     * "error_message": "Success",
     * "status": "success",
     * "auth_token": " example ",
     * "ACCOUNT_ID": " example ",
     * "ACCOUNT_REALM": " example ",
     * "OWNER_ID": " example ",
     * "OWNER_ROLE": " example "
     * }
     * @return array
     * @throws Exception
     */
    public function getWorkingKey()
    {
        $data = [
            "data" => [
                "login" => $this->credentials->login,
                "password" => $this->credentials->password,
                "realm" => $this->credentials->realm
            ]
        ];

        $ch = curl_init('http://api.zebratelecom.ru/v1/kazoos/user_auth');

        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $res = json_decode(curl_exec($ch), true);

        if ($res && isset($res['data']['auth_token']) && isset($res['data']['account_id'])) {
            $this->auth_token = $res['data']['auth_token'];
            $this->account_id = $res['data']['account_id'];
        } else {
            throw new Exception('Auth error!');
        }
    }


    /*
Получение статистики вызовов
Для получения необходимо сделать GET-запрос к http://api.zebratelecom.ru/v1/kazoos/accounts/ваш_ account_id /cdrs?auth_token=полученный_auth_token&created_from=63615445200&created_to=63618123599&filter_caller_id_number=171 (LEBEDEV LA)
Параметры created_from и created_to – время, обозначающая диапазон поиска. Подробнее о времени: таймстемпы в базе в григориан секундах и utc.
Чтобы из нужного времени (для сравнения из московского) получить значение для фильтра нужно: timestamp_msk - (3600*3) + 62167219200
или не вычитать 3 часа если таймстемп будет уже в utc
таймстемп - это время дисконнекта

filter_caller_id_number – добавление фильтра по сотруднику.
Более полная документация по CDRS находится в файле CDR+API

Ответ от сервера (в пример приведена только часть ответа):
{
"data": [
{
  "CALL_DIRECTION": "outbound",
  "BRIDGE_ID": "4241716746@192.168.0.7",
  "CALLEE_ID_NUMBER": "79221106306",
  "CALLER_ID_NUMBER": "171 (LEBEDEV LA)",
  "TIMESTAMP": "63615487767",
  "BILLING_SECONDS": "60",
  "HANGUP_CAUSE": "NORMAL_CLEARING",
  "ACCOUNT_ID": "bd393308c84b89c3a5c337652317bf98",
  "REC_FILE": "20151123-084927-bd393308c84b89c3a5c337652317bf98-call_recording_4241716746@192.168.0.7.mp3",
  "REC_LINK": "http://api.zebratelecom.ru/v1/kazoos/accounts/bd393308c84b89c3a5c337652317bf98/recorded/"
},
{
  "CALL_DIRECTION": "outbound",
  "BRIDGE_ID": "1448136767@192.168.0.7",
  "CALLEE_ID_NUMBER": "73432003288",
  "CALLER_ID_NUMBER": "171 (LEBEDEV LA)",
  "TIMESTAMP": "63615487677",
  "BILLING_SECONDS": "40",
  "HANGUP_CAUSE": "NORMAL_CLEARING",
  "ACCOUNT_ID": "bd393308c84b89c3a5c337652317bf98",
  "REC_FILE": "20151123-084757-bd393308c84b89c3a5c337652317bf98-call_recording_1448136767@192.168.0.7.mp3",
  "REC_LINK": "http://api.zebratelecom.ru/v1/kazoos/accounts/bd393308c84b89c3a5c337652317bf98/recorded/"
}
]
}
 */
    /**
     * @param $from
     * @param $to
     * @param $user
     * @return bool|array
     */
    public function request($from, $to, $number, $username)
    {
        $from += 62167219200;
        $to += 62167219200;

        $url = 'http://api.zebratelecom.ru/v1/kazoos/accounts/' . $this->account_id . '/cdrs?auth_token=' . $this->auth_token . '&created_from=' . $from . '&created_to=' . $to . '&filter_caller_id_number=' . mb_strtoupper($number . " ($username)", 'utf-8');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $res = json_decode(curl_exec($ch), true);

//        var_dump($res);exit;

        if ($res) {
            return $res;
        } else {
            return false;
        }
    }
}


