<?php
/**
 * @var yii/web/View $this
 * @var app\modules\script\models\ApiToken $token
 * @var array $formats
 */
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Url;
use yii\helpers\Html;

BootstrapAsset::register($this);
BootstrapPluginAsset::register($this);

$this->title = "Документация API. Версия: 2";

?>
<style>
    section {
        padding-bottom: 20px;
        margin-bottom: 60px;
        border-bottom: 1px solid #eee;
    }

    h5 {
        margin-top: 20px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-xs-9">
            <section id="basics">
                <h2>API версия 2</h2>

                <p>
                    Запросы к API идут по протоколу HTTP.
                </p>

                <p>
                    Все запросы имеют вид: <code><?php echo Url::to(['/integration/apiv2/controller/method', 'key' => 'API-KEY'], true) ?></code><br/>
                </p>
                <ul>
                    <li><code><?php echo Url::to(['/integration/apiv2/'], true) ?></code> &mdash; эта часть URL постоянна</li>
                    <li><code>controller/method</code> &mdash; роутинг для получения данных. Варианты будут описаны ниже.</li>
                    <li>
                        <code>key</code> &mdash; ключ API (так же можно передавать как HTTP-заголовок - <code>Key:Ваш Ключ API</code>)
                        <br/>
                        <br/>
                        <?php
                        if ($token) {
                            echo Html::tag('small', 'Ваш ключ API: <code>' . $token->token . '</code>');
                        }
                        ?>
                    </li>
                </ul>
                <br/>
                <br/>

                <table class="table table-striped">
                    <?php foreach ($formats as $format => $description): ?>
                        <tr>
                            <td><strong><?= $format ?></strong></td>
                            <td><?= $description ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>


            <section id="user/check">
                <h2>Проверка ключа API(<code>user/check</code>)</h2>

                <p>
                    Проверка правильности введенного пользователем ключа API.
                </p>

                <h5>URL запроса:</h5>
                <code><?php echo Url::to(['/integration/apiv2/user/check', 'key' => 'API-KEY'], true) ?></code>

                <h5>Пример ответа:</h5>
                <pre>{
  "key": 'ключ',
  "user_id": 0
}</pre>
            </section>

            <section id="user/list">
                <h2>Список пользователей (<code>user/list</code>)</h2>

                <p>
                    Выдает список ваших пользователей с их ролями и ключами авторизации.
                </p>

                <h5>URL запроса:</h5>
                <code><?php echo Url::to(['/integration/apiv2/user/list', 'key' => 'API-KEY'], true) ?></code>

                <h5>Пример ответа:</h5>
                <pre>[
    {"id":123456,"role":"user_head_manager","login":"main@domain.com","auth_token":"ключ 1"},
    {"id":123457,"role":"user_designer","login":"designer@domain.com","auth_token":"ключ 2"},
    {"id":123458,"role":"user_operator","login":"operator@domain.com","auth_token":"ключ 3"}
]</pre>

            </section>

            <section id="user/auth">
                <h2>Авторизация пользователя (<code>user/auth</code>)</h2>

                <p>
                    Авторизует пользователя по ID и токену авторизации.
                </p>

                <h5>URL запроса:</h5>
                <code><?php echo Url::to(['/integration/apiv2/user/auth', 'key' => 'API-KEY'], true) ?></code>

                <h5>Параметры:</h5>
                <ul>
                    <li>
                        <code>id</code> [обязательный] &mdash; ID пользователя

                    </li>
                    <li>
                        <code>token</code> [обязательный] &mdash; Токен авторизации пользователя
                    </li>
                </ul>
                <h5>Ответ:</h5>
                <p>
                    Переадресует на главную страницу.
                </p>

            </section>

            <section id="script/list">
                <h2>Список скриптов (<code>script/list</code>)</h2>

                <p>
                    Возвращает список скриптов пользователя. Каждый элемент списка содержит ID скрипта, название, и метку - опубликован скрипт или нет.
                    По-умолчанию возвращает все опубликованные скрипты.
                </p>

                <h5>URL запроса:</h5>
                <code><?php echo Url::to(['/integration/apiv2/script/list', 'key' => 'API-KEY'], true) ?></code>

                <h5>Пример ответа:</h5>
                <pre>
[
  {
    "id": 1617,
    "name": "Prohod Sekretaria",
    "published": 0
  },
  {
    "id": 460,
    "name": "Назначение встречи",
    "published": 1
  },
  {
    "id": 198,
    "name": "Smart4smart 3.0",
    "published": 1
  }
]</pre>
            </section>

            <section id="call/attach-record">
                <h2>Добавить запись звонка (<code>call/attach-record</code>)</h2>

                <p>
                    Прикрепляет к звонку URL записи телефонного разговора. URL должен быть доступен и вести к звуковому файлу - чтобы можно было прослушать при помощи онлайн плеера.
                </p>

                <h5>URL запроса:</h5>
                <code><?php echo Url::to(['/integration/apiv2/call/attach-record', 'key' => 'API-KEY', 'id' => 1234, 'url' => 'http://records-server.ru/record_123.mp3'], true) ?></code>

                <h5>Параметры:</h5>
                <ul>
                    <li>
                        <code>id</code> [обязательный] &mdash; ID звонка. Вы можете получить ID звонка, используя <a target="_blank" href="/integration/hookz">WebHooks</a>

                    </li>
                    <li>
                        <code>url</code> [обязательный] &mdash; URL записи разговора (urlencoded строка)
                    </li>
                </ul>
            </section>
        </div>

        <div class="col-xs-3">
            <nav class="bs-docs-sidebar affix">
                <ul class="nav nav-pills nav-stacked">
                    <li class=""><a href="#basics">API версия 2</a></li>
                    <li class=""><a href="#user/check">Проверка ключа API (user/check)</a></li>
                    <li class=""><a href="#user/list">Список пользователей (user/list)</a></li>
                    <li class=""><a href="#user/auth">Авторизация пользователя (user/auth)</a></li>
                    <li class=""><a href="#script/list">Список скриптов (script/list)</a></li>
                    <li class=""><a href="#call/attach-record">Добавить запись звонка (call/attach-record)</a></li>
                </ul>
                <a class="back-to-top" href="#top"> Наверх</a></nav>
        </div>
    </div>
</div>

