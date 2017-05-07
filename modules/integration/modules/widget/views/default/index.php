<?php
use yii\helpers\Html;
use app\modules\core\components\widgets\GlyphIcon;
use app\modules\core\components\Url;

/* @var $this yii\web\View */
/* @var boolean $enabled */


$this->title = Yii::t('widget', 'Widget');
$this->params['breadcrumbs'][] = ['label' => Yii::t('integration', 'Integrations'), 'url' => '/integration'];
$this->params['breadcrumbs'][] = $this->title;

?>
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<style>
    .container > pre.prettyprint {
        padding: 8px;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
    }
</style>
<div class="container">
    <legend>Виджет</legend>

    <p>
        Виджет позволяет работать со ScriptDesigner на странице любого сайта &mdash; к примеру на странице Google Docs или Битрикс 24.
    </p>

    <p>
        Вы можете воспользоваться им прямо сейчас, установив
        <a href="https://chrome.google.com/webstore/detail/scriptdesigner-widget/bpebppeoboloanldbdionokdabaacphg" target="_blank">расширение для Chrome</a>
        из официального каталога расширений Chrome.
    </p>

    <p>
        Если вам понравилось использовать виджет - оставьте отзыв в каталоге расширений Chrome или просто оцените звездочками.
    </p>

    <p>
        Если у вас возникнут какие либо проблемы при использовании виджета или появится идея - пишите на <?php echo Yii::$app->params['mails']['help'] ?>
    </p>
    <br/>
    <legend>Если вы владелец сайта (например, у вас своя CRM)</legend>

    <p>
        Виджет можно внедрить на ваш сайт, подключив скрипт в html-код.
    </p>

    <p>Это можно сделать, так:</p>
    <pre class="prettyprint  lang-js"><xmp>
            <script type="text/javascript">
                (function (d, w) {
                    var n = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        f = function () {
                            n.parentNode.insertBefore(s, n);
                        };
                    s.type = "text/javascript";
                    s.async = true;
                    s.src = "//scriptdesigner.ru/widget/v2/widget.js";

                    if (w.opera == "[object Opera]") {
                        d.addEventListener("DOMContentLoaded", f, false);
                    } else {
                        f();
                    }
                })(document, window);
            </script>
        </xmp></pre>
    <p>Или так</p>
    <pre class="prettyprint  lang-js"><xmp>
            <script type="text/javascript" src="https://scriptdesigner.ru/widget/v2/widget.js"></script>
        </xmp></pre>

    <br/>
    <br/>
    <legend>Техническая документация по виджету</legend>


    <p>
        Для работы виджета требуются библиотеки jQuery и jQuery UI
    </p>

    <p>
        Виджет не использует CSS и никак не может сломать верстку вашего сайта.
    </p>

    <p>
        После того, как загрузится widget.js - станет доступен конструктор <code>SSWidget</code>, который можно использовать для инициализации.
    </p>

    <p>
        Пример инициализации виджета:
    </p>

    <pre class="prettyprint  lang-js">window['widget_instance'] = new SSWidget();</pre>

    <p>
        Пример инициализации с настройками по-умолчанию:
    </p>

    <pre class="prettyprint  lang-js linenums">window['widget_instance'] = new SSWidget( {
        trace: false, // Включить\выключить трассировщик
        fixed: true, // Фиксировать виджет при скролле страницы или нет
        save_location: true, // Сохранять последний адрес iframe виджета
        namespace: "sswidget", // Пространство имен, используемое для всех идентификаторов в html-коде виджета
        title: "Script Designer", // Заголовок виджета
        messages: { // Тексты используемые в виджете
            fullscreen: 'На весь экран',
            default: 'К обычному состоянию',
            minimize: 'Свернуть',
            close: 'Закрыть'
        },
        images: { // кодированные иконки размером 20x20, используемые в виджете. Вы, например, можете заменить наш логотип на свой
            logo: 'data:image/png;base64,...',
            resizer: 'data:image/png;base64,...',
            home: 'data:image/png;base64,...',
            default: 'data:image/png;base64,...',
            minimize: 'data:image/png;base64,...',
            fullscreen: 'data:image/png;base64,...',
            close: 'data:image/png;base64...'
        },
        afterDestroy: function(){console.log('after destroy')}, // Callback вызываемый после уничтожения виджета
        afterInit: function(){console.log('after init')}, // Callback вызываемый после инициализации виджета
        default_state: { // Положение и размеры виджета по-умолчанию
            destroyed: false,
            view: SSWidgetViewType.DEFAULT,
            size: {
                width: 430,
                height: Math.round(430 * SSWidgetHelper.getRatio())
            },
            position: {
                left: -1, // Размещаем виджет в правом верхнем углу
                top: 0
            },
            location: "//scriptdesigner.ru/site/site/widget"
        },
        min_size: { // Минимально допустимый размер виджета
            width: 265,
            height: Math.round(265 * SSWidgetHelper.getRatio())
        }
    })</pre>

    <p>
        Если перед подключением файла widget.js вы выполните следующий код:
    </p>
    <pre class="prettyprint  lang-js">
window['SSWidgetInstance'] = 'my_widget_instance';
window['SSWidgetConfig'] = {trace:true,  title: "Свое крутое название для виджета"};</pre>
    <p>
        то при загрузке widget.js сразу инициализирует виджет в переменную <code>window['my_widget_instance']</code>,
        <br/>
        используя конфигурацию <code>{trace:true, title: "Свое крутое название для виджета"}</code> и подгрузит jQuery и jQuery UI если они не подгружены.
        <br/>
        Наличие <code>window['SSWidgetConfig']</code> не обязательно. Если этой переменной нет, виджет инициализируется с конфигурацией по-умолчанию.
    </p>


    <?php

    $methods = [
        '.init()' => 'Инициализация виджета (вызывается при конструировании)',
        '.destroy()' => 'Уничтожение виджета. После уничтожения виджет можно снова инициализировать функцией .init() Узнать уничтожен виджет в данный момент или нет можно в объекте .state',
        '.checkRequirements()' => 'Проверка наличия зависимостей (jQuery и jQuery UI)',
        '.flush()' => 'Сброс виджета в состояние по-умолчанию',
        '.defaultScreen()' => 'Отображение виджета ввиде небольшой панели (как он отображается по-умолчанию)',
        '.minimize()' => 'Свернуть виджет',
        '.fullScreen()' => 'Развернуть виджет на весь экран',
        '.sendData(data)' => 'Послать данные в iframe',
        '.changeLocation(url)' => 'Загрузить iframe определенным URL. Можно передать свои данные в звонок, используя hash-навигацию вида #/call/script_id/data/ваши данные (подробнее смотрите в документации для WebHooks)',
        '.state' => 'Объект, хранящий в себе текущее состояние виджета.',
    ];

    ?>


    <br/>

    <legend>Методы инициализврованного объекта SSWidget</legend>
    <table class="table table-striped">
        <?php foreach ($methods as $name => $description): ?>
            <tr>
                <td><code><?= $name ?></code></td>
                <td><?= $description ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<br/>
<br/>
<br/>
<br/>

