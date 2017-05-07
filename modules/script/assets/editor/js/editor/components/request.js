/**
 * Очередь запросов к серверу
 * @type {{}}
 */
RequestQueue = {};

RequestQueue._csrf = '';

/**
 * Массив, выполняющий роль очереди
 * @type {Request[]}
 */
RequestQueue.queue = [];

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

    var indicator = $('#editor___save_indicator');

    RequestQueue.working = false;

    var request = RequestQueue.queue.shift();

    var data = JSON.parse(JSON.stringify(request.data));

    data['_csrf'] = RequestQueue._csrf;

    indicator.text('Сохранение...');

    $.ajax({
        async: true,
        method: request.method,
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

            if (typeof request.onSuccess == 'function') {
                request.onSuccess(data, textStatus, jqXHR);
            }

            indicator.text('Сохранено');

            Yiij.trace('Продолжаю работу очереди...');

            RequestQueue.working = true;
        },

        /**
         * Выполнится при неуспешном завершении запроса
         * @param {jqXHR} jqXHR
         * @param {string} textStatus
         * @param {string} errorThrown
         */
        error: function (jqXHR, textStatus, errorThrown) {
            if (typeof request.onError == 'function') {
                request.onError(jqXHR, textStatus, errorThrown);
            }

            Yiij.trace('Произошла ошибка!');
            indicator.text('Ошибка сервера');
            Yiij.app.getModule('editor').messenger.overlay("<span class='text-danger'>Произошла ошибка:</span><br/>" + jqXHR.responseJSON.message + "<br/><br/><br/> <span class='text-danger'>Пришлите текст ошибки на support@scriptdesigner.ru с указанием своего логина и номера скрипта</span>");
        }
    })
};

// Петля обработки очереди
setInterval(function () {
    if (RequestQueue.working && RequestQueue.queue.length) {
        RequestQueue.send();
    }
}, 100);

/**
 * Объект запроса к серверу
 *
 * @param config
 * @constructor
 */
var Request = function (config) {
    YiijBaseComponent.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseComponent}
 */
Request.prototype = Object.create(YiijBaseComponent.prototype);
Request.prototype.constructor = Request;

/**
 * @type {{}}
 */
Request.prototype.data = {};

/**
 * @type {string}
 */
Request.prototype.url = '';

/**
 * @type {string}
 */
Request.prototype.method = '';

/**
 * @type {function}
 */
Request.prototype.onSuccess = null;

/**
 * @type {function}
 */
Request.prototype.onError = null;