<?php
namespace app\modules\integration\modules\amo\components;


class AmoRequest
{
    const AUTH = 1;
    const INFO = 2;
    const GET = 3;
    const SET = 4;

    public $is_auth;
    public $post;
    public $url;
    public $type;
    public $action;


    public $params;

    /**
     * @var AmoApi
     */
    private $object;

    public function __construct($request_type = null, $params = null, $object = null)
    {
        $this->post = false;
        $this->params = $params;
        $this->object = $object;

        switch ($request_type) {
            case AmoRequest::AUTH:
                $this->createAuthRequest();
                break;
            case AmoRequest::INFO:
                $this->createInfoRequest();
                break;
            case AmoRequest::GET:
                $this->createGetRequest();
                break;
            case AmoRequest::SET:
                $this->createPostRequest();
                break;
        }
    }

    private function createAuthRequest()
    {
        $this->is_auth = true;
        $this->post = true;
        $this->url = 'auth.php?type=json';

        $this->params = [
            'USER_LOGIN' => $this->params->user->amouser,
            'USER_HASH' => $this->params->user->amohash
        ];
    }

    private function createInfoRequest()
    {
        $this->url = 'v2/json/accounts/current';
    }

    private function createGetRequest()
    {
        $this->url = 'v2/json/' . $this->object[0] . '/' . $this->object[1];

        if (count($this->params)) {
            $i = 1;
            foreach ($this->params as $key => $value) {
                $this->url .= ($i == 1) ? '?' : '&';
                $this->url .= $key . '=' . $value;
                $i++;
            }
        }
    }

    private function createPostRequest()
    {
        if (!is_array($this->params)) {
            $this->params = [$this->params];
        }

        $type = $this->params[0]->type;
        $id = $this->params[0]->id;

        $action = (isset($id)) ? 'update' : 'add';
        $params = [];
        $params['request'][$type][$action] = $this->params;

        $this->post = true;
        $this->type = $type;
        $this->action = $action;
        $this->url = 'v2/json/' . $this->type . '/set';
        $this->params = $params;
    }
}
