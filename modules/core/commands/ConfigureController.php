<?php

namespace app\modules\core\commands;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\db\Connection;


/**
 * Class ConfigureController
 * @package app\modules\core\commands
 */
class ConfigureController extends Controller
{

    /**
     * Генерит кронтаб для приложения
     *
     * Туториал по крону https://masterhost.ru/support/doc/cron/
     */
//    public function actionCron()
//    {
//        $scripts = [
////            'hit/add-partition' => '15 1 * * 1' // выполнять попытку создать партицию для таблицы hit каждый понедельник в 1 час 15 минут ночи
//        ];
//
//        $result = '';
//
//        foreach ($scripts as $name => $conf) {
//            $result .= $conf . ' ' . 'php ' . Yii::getAlias('@app') . '/yii ' . $name . ';' . PHP_EOL;
//        }
//
//        file_put_contents('/tmp/crontab.txt', $result);
//        exec('crontab /tmp/crontab.txt');
//
//        echo $result;
//    }

    /**
     * Создает БД Mysql и конфиг config/db.php Пример: php yii configure/db root toortoor node1 1 col mY80Kpxdhg
     *
     * @param string $username Имя пользователя под которым будем логиниться в Mysql
     * @param string $password пароль
     * @param string $db_name Название базы
     * @param bool $create_db Создавать ли БД
     * @param null $db_user Имя пользователя базы данных (можно не указывать)
     * @param null $db_user_password Имя пользователя базы данных (можно не указывать)
     *
     * @throws \yii\db\Exception
     */
    public function actionDb($username, $password, $db_name, $create_db = false, $db_user = null, $db_user_password = null)
    {
        $db = new Connection([
            'dsn' => 'mysql:host=localhost',
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8'
        ]);

        if ($create_db) {
            echo "Создание базы данных $db_name" . PHP_EOL;
//            $db->createCommand("DROP DATABASE `$db_name`;")->execute();
            $db->createCommand("CREATE DATABASE `$db_name` CHARACTER SET utf8 COLLATE utf8_general_ci;")->execute();

            if ($db_user) {
                echo "Создание пользователя $db_user и выдача ему прав на базу данных $db_name..." . PHP_EOL;

                $db->createCommand("CREATE USER '$db_user'@'localhost' IDENTIFIED BY '$db_user_password'; GRANT ALL PRIVILEGES ON $db_name . * TO '$db_user'@'localhost'; FLUSH PRIVILEGES;")->execute();
            }
        }

        if (!$db_user) {
            $db_user = $username;
            $db_user_password = $password;
        }

        echo "Заполнение конфигурационного файла config/generated/db.php ..." . PHP_EOL;
        file_put_contents(Yii::getAlias('@app/config') . '/generated/db.php', "<?php\nreturn [ 'class' => 'yii\db\Connection', 'dsn' => 'mysql:host=localhost;dbname=$db_name', 'username' => '$db_user', 'password' => '$db_user_password', 'charset' => 'utf8'];");
    }

    /**
     * Создает конфигурационные файлы для DEV-окружения.
     *
     * При наличии этих файлов приложение переходит в DEV-режим.
     *
     * @throws \yii\db\Exception
     */
    public function actionDev()
    {
        $web = <<<PHP
<?php
\$config = require_once __DIR__ .'/../web.php';

\$config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    'allowedIPs' => ['127.0.0.1', '192.168.56.*']
];

\$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => ['127.0.0.1', '192.168.56.*']
];

\$config['bootstrap'][] = 'gii';
\$config['bootstrap'][] = 'debug';

return \$config;
PHP;

        file_put_contents(Yii::getAlias('@app/config') . '/generated/web.dev.php', $web);

        $console = <<<PHP
<?php
\$config = require_once __DIR__ .'/../console.php';

return \$config;
PHP;

        file_put_contents(Yii::getAlias('@app/config') . '/generated/console.dev.php', $console);

        echo 'Конфигурационныей файлы config/generated/web.dev.php и config/generated/console.dev.php созданы.' . PHP_EOL;
    }

    /**
     * Создание и активация конфига для Nginx. [Имя сервера] Пример: sudo php yii core/configure/nginx one /home/one/apps/one 7.1
     *
     * Создаст в папке /etc/nginx/sites-available/ файл с конфигом сервера и активирует его.
     * Выполнять команду нужно через sudo
     *
     * @param string $server Имя сервера
     * @param string $root Корневая директория (используется при локальном развертывании, если папка с проектом монтируется отдельно для nginx)
     * @param string $fastcgi_pass Сокет или сетевая петля, на которую будут проксироваться запросы для PHP
     * @return int
     *
     * /run/php/php7.1-fpm.sock
     */
    public function actionNginx($server, $root = null, $fastcgi_pass = "unix:/var/run/php/php7.1-fpm.sock")
    {
        if (!$root) {
            $root = Yii::getAlias('@app');
        }

        $content = 'server {
    listen          80;
    server_name     ' . $server . ';
    charset         utf-8;
    gzip            on;
    ssi             off;
    client_max_body_size 15M;

    set $application_root ' . $root . ';
    set $err_log $application_root/runtime/nginx/error.log;
    set $acc_log $application_root/runtime/nginx/access.log;
    set $site_document_root $application_root/public_html;

    error_log $err_log;
    access_log $acc_log;
    root $site_document_root;

    location @phpfcgi {
        internal;
        fastcgi_buffers 16 16k;
        fastcgi_pass    ' . $fastcgi_pass . '; #127.0.0.1:9000;
        include         /etc/nginx/fastcgi_params;
        fastcgi_index   index.php;
        fastcgi_param   REQUEST_URI     $request_uri;
        fastcgi_param   SCRIPT_NAME     /index.php;
        fastcgi_param   SCRIPT_FILENAME $site_document_root/index.php;
    }

    location ~ \.(php|php/.*)$ {
        fastcgi_buffers 16 16k;
        fastcgi_pass    ' . $fastcgi_pass . '; #127.0.0.1:9000;
        include         /etc/nginx/fastcgi_params;
        fastcgi_param   SCRIPT_FILENAME $site_document_root$fastcgi_script_name;
    }
    location / {
        error_page      404 = @phpfcgi;
        index   index.php;
    }
}';

        $config_filename = '/etc/nginx/sites-available/' . $server;
        $enabled_link_name = '/etc/nginx/sites-enabled/' . $server;

        file_put_contents($config_filename, $content);
        if (!file_exists($enabled_link_name)) {
            exec("ln -s $config_filename $enabled_link_name");
        }

        exec("service nginx restart");
    }

    /**
     * Создание и активация конфига для Nginx. [Имя сервера] Пример: sudo php yii core/configure/nginx one /home/one/apps/one 7.1
     *
     * Создаст в папке /etc/nginx/sites-available/ файл с конфигом сервера и активирует его.
     * Выполнять команду нужно через sudo
     *
     * @param string $server Имя сервера
     * @param string $root Корневая директория (используется при локальном развертывании, если папка с проектом монтируется отдельно для nginx)
     * @param string $fastcgi_pass Сокет или сетевая петля, на которую будут проксироваться запросы для PHP
     * @param string $sertificate_file Путь до файла сертификата
     * @param string $key_file Путь до файла ключа
     * @return int
     *
     * /run/php/php7.1-fpm.sock
     */
    public function actionNginxSsl($server, $root = null, $fastcgi_pass = "unix:/var/run/php/php7.1-fpm.sock", $sertificate_file = null, $key_file = null)
    {
        if (!$root) {
            $root = Yii::getAlias('@app');
        }

        if (!$sertificate_file) {
            $sertificate_file = $root . '/ssl/' . $server . '.crt';
        }

        if (!file_exists($sertificate_file)) {
            throw new Exception('Не найден файл: ' . $sertificate_file);
        }

        if (!$key_file) {
            $key_file = $root . '/ssl/' . $server . '.key';
        }

        if (!file_exists($key_file)) {
            throw new Exception('Не найден файл: ' . $key_file);
        }

        exec("chmod 0644 $key_file");
        exec("chmod 0644 $sertificate_file");

        $content = 'server {
    listen                  80;
    listen                  443 ssl;
    server_name             ' . $server . ';
    charset                 utf-8;
    gzip                    on;
    ssi                     off;
    client_max_body_size    15M;
    keepalive_timeout       60;

    set $application_root ' . $root . ';
    set $site_document_root $application_root/public_html;

    ssl_certificate ' . $sertificate_file . ';
    ssl_certificate_key ' . $key_file . ';
    ssl_verify_depth 3;
    error_log /var/log/nginx/' . $server . '.error.log;
    access_log /var/log/nginx/' . $server . 'access.log;
    root $site_document_root;

    location @phpfcgi {
        internal;
        fastcgi_param HTTPS on;
        fastcgi_buffers 16 16k;
        fastcgi_pass    ' . $fastcgi_pass . '; #127.0.0.1:9000;
        include         /etc/nginx/fastcgi_params;
        fastcgi_index   index.php;
        fastcgi_param   REQUEST_URI     $request_uri;
        fastcgi_param   SCRIPT_NAME     /index.php;
        fastcgi_param   SCRIPT_FILENAME $site_document_root/index.php;
    }

    location ~ \.(php|php/.*)$ {
        fastcgi_param HTTPS on;
        fastcgi_buffers 16 16k;
        fastcgi_pass    ' . $fastcgi_pass . '; #127.0.0.1:9000;
        include         /etc/nginx/fastcgi_params;
        fastcgi_param   SCRIPT_FILENAME $site_document_root$fastcgi_script_name;
    }

    location / {
        error_page      404 = @phpfcgi;
        index   index.php;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }
}';

        $config_filename = '/etc/nginx/sites-available/' . $server;
        $enabled_link_name = '/etc/nginx/sites-enabled/' . $server;

        file_put_contents($config_filename, $content);

        if (!file_exists($enabled_link_name)) {
            exec("ln -s $config_filename $enabled_link_name");
        }

        exec("service nginx restart");
    }
}
