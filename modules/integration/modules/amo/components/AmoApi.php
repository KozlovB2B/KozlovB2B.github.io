<?php

namespace app\modules\integration\modules\amo\components;

use app\modules\integration\modules\amo\models\AmoUser;

class AmoApi
{
    private $errors;

    /**
     * @var AmoUser
     */
    public $user;

    public $result;

    public $last_insert_id;

    /**
     * @param AmoUser $user
     */
    public function __construct(AmoUser $user)
    {
        $this->user = $user;
    }

    /**
     * @throws \Exception
     */
    public function auth()
    {
        return $this->request(new AmoRequest(AmoRequest::AUTH, $this));
    }

    /**
     * @param AmoRequest $request
     * @return $this
     * @throws \Exception
     */
    public function request(AmoRequest $request)
    {
        $url = 'https://' . $this->user->subdomain . '.amocrm.ru/private/api/' . $request->url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->user->cookieFile());
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->user->cookieFile());

        if ($request->post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->params));
        }

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \Exception($error);
        }

        $this->result = json_decode($result);
//        var_dump( $this->result);exit;


        if (floor($info['http_code'] / 100) >= 3) {
            if (!YII_DEBUG) {
                $message = $this->result->response->error;
            } else {
                $error = (isset($this->result->response->error)) ? $this->result->response->error : '';
                $error_code = (isset($this->result->response->error_code)) ? $this->result->response->error_code : '';
                $description = ($error && $error_code && isset($this->errors->{$error_code})) ? $this->errors->{$error_code} : '';
                $response = (isset($this->result->response->error)) ? $this->result->response->error : '';

                $message = json_encode([
                    'http_code' => $info['http_code'],
                    'response' => $response,
                    'description' => $description
                ], JSON_UNESCAPED_UNICODE);
            }

            throw new \Exception($message);
        }

        $this->result = isset($this->result->response) ? $this->result->response : false;
        $this->last_insert_id = ($request->post && isset($this->result->{$request->type}->{$request->action}[0]->id))
            ? $this->result->{$request->type}->{$request->action}[0]->id
            : false;

        return $this;
    }
}


