<?php
namespace app\modules\integration\modules\onlinepbx\components;

use app\modules\integration\modules\onlinepbx\models\ApiCredentials;
use yii\base\Exception;

class OnlinepbxApi
{
    private $errors;

    /**
     * @var ApiCredentials
     */
    public $credentials;

    /**
     * @var string Working key for requests
     */
    public $working_key;

    /**
     * @var string Working key id for requests
     */
    public $working_key_id;

    public function __construct(ApiCredentials $credentials)
    {
        $this->credentials = $credentials;
        $this->getWorkingKey();
    }

    /**
     * Auth in Online PBX api - gets working key
     *
     * @return array
     * @throws Exception
     */
    public function getWorkingKey()
    {
        $data = array('auth_key' => $this->credentials->key);
        $data['new'] = 'true';

        $ch = curl_init('http://api.onlinepbx.ru/' . $this->credentials->domain . '/auth.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $res = json_decode(curl_exec($ch), true);

        if ($res && isset($res['status']) && $res['status'] == 1 && isset($res['data']['key']) && isset($res['data']['key_id'])) {
            $this->working_key = $res['data']['key'];
            $this->working_key_id = $res['data']['key_id'];
        } else {
            throw new Exception('Wrong key!');
        }
    }

    /**
     * @param $url
     * @param array $post
     *
     * @return array
     */
    public function request($url, $post = [])
    {
        return $this->onpbx_api_query($this->working_key, $this->working_key_id, 'api.onlinepbx.ru/' . $this->credentials->domain . '/' . $url, $post);
    }

    function onpbx_api_query($secret_key, $key_id, $url, $post=array(), $opt=array()){
        $method = 'POST';
        $date = @date('r');

        if (is_array($post)){
            foreach ($post as $key => $val){
                if (is_string($key) && preg_match('/^@(.+)/', $val, $m)){
                    $post[$key] = array('name'=>basename($m[1]), 'data'=>base64_encode(file_get_contents($m[1])));
                }
            }
        }
        $post = http_build_query($post);
        $content_type = 'application/x-www-form-urlencoded';
        $content_md5 = hash('md5', $post);
        $signature = base64_encode(hash_hmac('sha1', $method."\n".$content_md5."\n".$content_type."\n".$date."\n".$url."\n", $secret_key, false));
        $headers = array('Date: '.$date, 'Accept: application/json', 'Content-Type: '.$content_type, 'x-pbx-authentication: '.$key_id.':'.$signature, 'Content-MD5: '.$content_md5);

        if (isset($opt['secure']) && $opt['secure']){
            $proto = 'https';
        }else{
            $proto = 'http';
        }
        $ch = curl_init($proto.'://'.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
//        echo curl_exec($ch);exit;
        $res = json_decode(curl_exec($ch), true);

        if ($res){return $res;}else{return false;}
    }
}


