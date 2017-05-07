<?php

namespace app\modules\script\components;


use yii\base\Component;
use phpcent\Client;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class WSConnection extends Component
{
    /**
     * @var Client
     */
    protected static $_client;

    protected static $_port = 8001;

    /**
     * @var string
     */
    protected static $_secret = "d413f198-fc9f-4ba7-ac0e-f55a2e70669a";

    /**
     * Запускает центрифугу
     */
    public static function runDaemon()
    {
        $command = 'centrifugo --config="' . Yii::getAlias('@app/config') . '/centrifugo.json" --log_file="' . Yii::getAlias('@app/runtime/logs') . '/centrifugo.log"';

        echo $command;
        exit;
    }

    /**
     * Возвращает объект клиента. Пробует запустить демон если не удалось соединиться
     *
     * @return Client
     */
    public static function getClient()
    {
        if (static::$_client === null) {
            try {
                static::$_client = new Client("http://localhost:" . static::$_port);
                static::$_client->setSecret(static::$_secret);
                static::$_client->stats();
            } catch (\Exception $e) {
                static::runDaemon();
            }
        }

        return static::$_client;
    }

    /**
     * Название канала редактирования скрипта
     *
     * @param $script_id
     * @return string
     */
    public static function scriptEditingChannelName($script_id)
    {
        return 'script-editor:edit-' . $script_id;
    }

    /**
     * Генерирует конфигурацию для объекта на клиента
     *
     * @param int $script_id ID скрипта для которого получаем конфигурацию
     * @return string
     */
    public static function getJsConfig($script_id)
    {
        $timestamp = time();

        $user_id = 'u' . Yii::$app->getUser()->getId();

        return Json::encode([
            'user' => $user_id,
            'channel' => static::scriptEditingChannelName($script_id),
            'token' => static::getClient()->setSecret(static::$_secret)->generateClientToken($user_id, $timestamp),
            'timestamp' => $timestamp . '',
            'url' => YII_ENV == "prod" ? trim(Url::to('/', true), '/') . '/centrifugo' : trim(Url::to('/', true), '/') . ":" . static::$_port,
        ]);
    }

}