<?php
/**
 * @var yii/web/View $this
 */
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Url;
use yii\helpers\Html;


BootstrapAsset::register($this);
BootstrapPluginAsset::register($this);

$base_url = Url::to(['/api/v1/controller/method', 'key' => 'API-KEY'], true);

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
    <h1 id="top" class="page-header">Документация API ScripDesigner. Версия: v1</h1>

    <div class="row">
        <div class="col-xs-9">
            <section id="basics">
                <h2 class="page-header">Основные положения</h2>

                <p>
                    Запросы к API идут по протоколу HTTP.
                </p>

                <p>
                    Формат ответа JSON - если запрашиваются данные, HTML если пользователю выдается какой либо интерфейс.
                </p>

                <p>
                    Все запросы имеют вид: <code><?php echo Html::a($base_url, $base_url) ?></code><br/>
                </p>
                <ul>
                    <li><code><?php echo Url::to(['/api/v1/'], true) ?></code> &mdash; эта часть URL постоянна</li>
                    <li><code>controller/method</code> &mdash; роутинг для получения данных. Варианты будут описаны ниже.</li>
                    <li><code>key</code> &mdash; ключ API зарегистрированного пользователя, <br/>
                        можно узнать после входа в систему на странице <?php echo Html::a(Url::to(['/profile'], true), Url::to(['/profile'], true)) ?></li>
                </ul>
                <small>
                    * Можно передать так же параметр <code>format</code> (json, xml) - текстовый формат в котором API будет отвечать.<br/>
                    По-умолчанию API отвечает в формате json. Не работает в случае методов, возвращающих пользователю HTML страницу.
                </small>
            </section>


            <section id="check/key">
                <h2 class="page-header">Проверка ключа API(<code>check/key</code>)</h2>

                <p>
                    Проверка правильности введенного пользователем ключа API.
                </p>

                <h5>Пример запроса:</h5>
                <code><?php echo Url::to(['/api/v1/check/key', 'key' => 'API-KEY'], true) ?></code>

                <h5>Пример ответа:</h5>
                <pre>{
  "key_valid": 0,
  "user_id": 0
}</pre>
                или
                <pre>{
  "key_valid": 1,
  "user_id": 96
}</pre>
            </section>


            <section id="script/list">
                <h2 class="page-header">Список скриптов (<code>script/list</code>)</h2>

                <p>
                    Возвращает список скриптов пользователя. Каждый элемент списка содержит ID скрипта, название, и метку - опубликован скрипт или нет.
                    По-умолчанию возвращает все опубликованные скрипты.
                </p>

                <h5>Пример запроса:</h5>
                <code><?php echo Url::to(['/api/v1/script/list', 'key' => 'API-KEY'], true) ?></code>

                <h5>Возможные параметры:</h5>
                <ul>
                    <li>
                        <code>all</code> [необязательный] &mdash; передайте all=1, чтобы получить полный список скриптов пользователя (включая неопубликованные).
                        По-умолчанию <code>all</code> равен 0 (возвращаются только опубликованные скрипты)
                    </li>
                </ul>

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

            <section id="call/perform">
                <h2 class="page-header">Выполнение звонка (<code>call/perform</code>)</h2>

                <p>
                    Показывает HTML страницу с интерфейсом выполнения звонка (прогона скритпа).
                    По завершению звонка - данные сохраняются в базу, пользователя перенаправляет на страницу с результатом звонка.
                </p>

                <h5>Пример запроса:</h5>
                <code><?php echo Url::to(['/api/v1/call/perform', 'key' => 'API-KEY', 'script_id' => 1234], true) ?></code>

                <h5>Возможные параметры:</h5>
                <ul>
                    <li>
                        <code>script_id</code> [обязательный] &mdash; ID скрипта, по которому будет проходить звонок. Список скриптов можно получить при помощи метода: <a href="#script/list">script/list</a>
                    </li>
                    <li>
                        <code>user_login</code> [необязательный] &mdash; Логин пользователя в вашей системе, который совершает звонок. Он будет задействован в построении отчетов по звонкам в ScriptDesigner.
                    </li>
                    <li>
                        <code>callback</code> [необязательный] &mdash; кодированный URL к которому обратится наш сервер после завершения звонка.
                        <br/>
                        URL может содержать маркеры - например <code>[call_id]</code>. Вместо них сервер подставит данные звонка.<br/>
                        Список маркеров:
                        <ul>
                            <li><code>_call_id_</code> &mdash; ID звонка</li>
                            <li><code>_user_id_</code> &mdash; ID пользователя, который совершал звонок</li>
                            <li><code>_script_id_</code> &mdash; ID скрипта по которому был сделан звонок</li>
                            <li><code>_is_goal_reached_</code> &mdash; Достигнута ли цель</li>
                            <li><code>_normal_ending_</code> &mdash; Штатно или нет завершился скрипт</li>
                        </ul>
                        <br/>
                        Например <code>callback</code> может быть таким: <code>http%3A%2F%2Fserver.com%2Fendcall.php%3Fcall_id%3D_call_id_</code>
                        <br/>
                        <br/>
                        Если работаете с PHP можете формировать такую строку используя urlencode:
                        <pre>$callback = urlencode("http://myserver.com/endcall.php?call_id=_call_id_")</pre>
                        <br/>
                        Используя <code>callback</code> вы можете передавать данные в свою систему непосредственно в момент завершения звонка и собирать данные по звонкам.
                    </li>
                </ul>
            </section>

            <section id="call/result">
                <h2 class="page-header">Результаты звонка (<code>call/result</code>)</h2>

                <p>
                    Показывает HTML страницу с результатами звонка, включая лог прогона скрипта
                </p>

                <h5>Пример запроса:</h5>
                <code><?php echo Url::to(['/api/v1/call/result', 'key' => 'API-KEY', 'id' => 1234], true) ?></code>

                <h5>Возможные параметры:</h5>
                <ul>
                    <li>
                        <code>id</code> [обязательный] &mdash; ID звонка. Вы можете получить его, используя <code>callback</code>, который применяется и подробно описан в методе <a href="#call/perform">call/perform</a>
                    </li>
                </ul>
            </section>

            <section id="call/attach-record">
                <h2 class="page-header">Добавить запись звонка (<code>call/attach-record</code>)</h2>

                <p>
                    Прикрепляет к звонку URL к звуковой записи телефонного разговора. URL должен быть доступен и вести к звуковому файлу - чтобы можно было прослушать при помощи онлайн плеера.
                </p>

                <h5>Пример запроса:</h5>
                <code><?php echo Url::to(['/api/v1/call/attach-record', 'key' => 'API-KEY', 'id' => 1234, 'url' => 'http://records-server.ru/record_123.mp3'], true) ?></code>

                <h5>Возможные параметры:</h5>
                <ul>
                    <li>
                        <code>id</code> [обязательный] &mdash; ID звонка. Вы можете получить его, используя <code>callback</code>, который применяется и подробно описан в методе <a href="#call/perform">call/perform</a>
                        <code>url</code> [обязательный] &mdash; URL записи разговора</a>
                    </li>
                </ul>
            </section>
        </div>

        <div class="col-xs-3">
            <nav class="bs-docs-sidebar affix">
                <ul class="nav nav-pills nav-stacked">
                    <li class=""><a href="#basics">Основные положения</a></li>
                    <li class=""><a href="#check/key">Проверка ключа API (check/key)</a></li>
                    <li class=""><a href="#script/list">Список скриптов (script/list)</a></li>
                    <li class=""><a href="#call/perform">Выполнение звонка (call/perform)</a></li>
                    <li class=""><a href="#call/perform">Результаты звонка (call/result)</a></li>
                    <li class=""><a href="#call/attach-record">Добавить запись звонка (call/attach-record)</a></li>
                </ul>
                <a class="back-to-top" href="#top"> Наверх</a></nav>
        </div>
    </div>
</div>

