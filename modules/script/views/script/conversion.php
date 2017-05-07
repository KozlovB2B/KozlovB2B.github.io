<?php

use app\modules\billing\models\Account as BillingAccount;


$this->title = 'Конвертация скриптов в новый формат. Не закрывайте страницу.';

/* @var $this yii\web\View */
/* @var $scripts_data_provider yii\data\ActiveDataProvider */
/** @var BillingAccount $billing */
?>
<div class="row">

    <div class="
            col-lg-4 col-lg-offset-4
            col-md-6 col-md-offset-3
            col-sm-6 col-sm-offset-3
            col-xs-8 col-xs-offset-2">
        <h4 class="text-center">Идет конвертация скриптов в новый формат</h4>
        <h5 class="text-danger text-center">Не закрывайте страницу, пока все скрипты не сконвертируются.</h5>
        <br/>

        <div id="error" class="alert alert-danger" style="display: none">
            Сделайте скриншот этой страницы и отправьте на <?php echo Yii::$app->params["supportEmail"] ?>
        </div>

        <ul class="list-group" id="conversion_list">
            <?php
            foreach ($scripts_data_provider->getModels() as $script) {
                ?>
                <li class="list-group-item" id="script-<?= $script->id; ?>">
                    <strong>
                        #<?= $script->id; ?>
                    </strong>
                    <?= $script->name ? $script->name : 'Без названия' ?>

                    <span class="status pull-right">

                        <i class="glyphicon glyphicon-refresh"></i>

                        <i class="glyphicon glyphicon-ok text-success"></i>
                        <i class="glyphicon glyphicon-warning-sign text-danger"></i>

                    </span>

                    <div class="error text-danger"></div>
                </li>
                <?php $this->registerJs("RequestQueue.add({'id':'" . $script->id . "','url' : '/script/script/convert?id=" . $script->id . "'});") ?>
                <?php
            }
            ?>
        </ul>
    </div>
</div>

<?php $this->registerJs('

    window["sdf"] = setInterval(function () {
        if(!RequestQueue.queue.length && !RequestQueue.errors){
            clearInterval(window["sdf"]);
            //window.location.href = "/";
        }
        if (RequestQueue.working && RequestQueue.queue.length) {
            RequestQueue.send();
        }
    }, 100);
'); ?>

<style>
    .status {
        font-size: 16px;
    }

    #conversion_list .list-group-item {
        opacity: 0.5;
        transition: 1s;
    }

    #conversion_list .list-group-item.proceed {
        opacity: 1;
    }

    #conversion_list .list-group-item .glyphicon {
        display: none;
    }

    #conversion_list .list-group-item.proccess .glyphicon-refresh {
        display: inline;
    }

    #conversion_list .list-group-item.success .glyphicon-ok {
        display: inline;
    }

    #conversion_list .list-group-item.error .glyphicon-warning-sign {
        display: inline;
    }
</style>
<script>

    /**
     * Очередь запросов к серверу
     * @type {{}}
     */
    RequestQueue = {};

    RequestQueue._csrf = '<?php echo Yii::$app->getRequest()->csrfToken ?>';

    /**
     * Массив, выполняющий роль очереди
     * @type {Request[]}
     */
    RequestQueue.queue = [];
    RequestQueue.errors = false;

    /**
     * Турникет очереди
     * @type {boolean}
     */
    RequestQueue.working = true;

    /**
     * Добавить запрос в очередь
     * @param request
     */
    RequestQueue.add = function (request) {
        Yiij.trace('Добавляю запрос в очередь для отправки на сервер.');
        Yiij.trace(request);

        RequestQueue.queue.push(request);
    };

    /**
     * Посылает первый в очереди запрос на сервер.
     * Если сервер ответил успешно - посылает следующий запрос.
     * Если сервер выдал ошибку - останавливает очередь
     * @returns {boolean}
     */
    RequestQueue.send = function () {
        if (!RequestQueue.queue.length) {
            return false;
        }

        RequestQueue.working = false;

        var request = RequestQueue.queue.shift();

        var list_item = $('#script-' + request.id);

        list_item.addClass('proceed');
        list_item.addClass('proccess');

        var data = {};

        data['_csrf'] = RequestQueue._csrf;

        $.ajax({
            async: true,
            method: 'GET',
            url: request.url,
            dataType: 'json',
            data: data,

            /**
             * Выполнится при успешном завершении запроса
             * @param {*} data
             * @param {string} textStatus
             * @param {jqXHR} jqXHR
             */
            success: function (data, textStatus, jqXHR) {
                Yiij.trace('Продолжаю работу очереди...');
                RequestQueue.working = true;
                list_item.removeClass('proccess');
                list_item.addClass('success');
            },

            /**
             * Выполнится при неуспешном завершении запроса
             * @param {jqXHR} jqXHR
             * @param {string} textStatus
             * @param {string} errorThrown
             */
            error: function (jqXHR, textStatus, errorThrown) {
                RequestQueue.errors = true;
                RequestQueue.working = true;
                list_item.removeClass('proccess');
                $('#error').show();
                list_item.addClass('error');
                list_item.find('.error').html(jqXHR.responseJSON.message);
            }
        })
    };

    // Петля обработки очереди


</script>