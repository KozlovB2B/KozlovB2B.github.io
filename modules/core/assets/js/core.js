String.prototype.fmt = function (hash) {
    var string = this, key;
    for (key in hash) string = string.replace(new RegExp('\\{' + key + '\\}', 'gm'), hash[key]);
    return string
};

function guid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

/**
 * Close current modal
 * @param btn - объект самой кнопки, на которую щелкнули
 * @returns {boolean}
 */
function closeModal(btn) {
    var modal_window = $($(btn).closest("div.modal").get(0));
    modal_window.modal("hide");
    return false;
}

/**
 * Form resetting shortcut
 * @param form_id
 */
function resetForm(form_id) {
    $('#' + form_id)[0].reset();
}

function clearFormErrors(id_form) {
    var form = $("#" + id_form);
    form.find(".has-error").removeClass("has-error");
    form.find(".help-block").text("");
}

function ajaxForm(id_form, ajaxMessage, onSuccess) {
    var form = $("#" + id_form);
    clearFormErrors(id_form);
    var url = form.attr('action');
    var submit_button = form.find('[type="submit"]');
    ajax(url, form.serialize(), onSuccess, function (data) {
        for (var id in data) {
            if (id == "message") {
                if (typeof data[id] == "object" || typeof data[id] == "array") {
                    showMessage('error', data[id].join(", "));
                } else {
                    showMessage('error', data[id]);
                }
            }
            var f = $("#" + id);
            if (f.length) {
                f.parent().addClass("has-error");
                f.siblings(".help-block").text(data[id].join(", "));
            }
        }
        submit_button.attr('disabled', false);
    }, ajaxMessage);
}

function submitForm(id_form, ajaxMessage, onSuccess) {
    var form = $("#" + id_form);
    ajax(form.attr('action'), new FormData(form), onSuccess, function (data) {
        showMessage('error', data.message);
    }, ajaxMessage);
}

/**
 * Добавляет пробелы через каждые N символов в строке
 * Используется для форматирования чисел больше от 1000 и больше
 *
 * @param str - строка, которую форматируем
 * @param interval - с каким интервалом между символами вставляем пробелы
 */
function addSpaces(str, interval) {
    if (typeof interval == 'undefined') {
        interval = 3;
    }

    var str_new = '';

    for (i in str) {
        if ((
                str.length - i
            ) % interval == 0) {
            str_new += ' ';
        }
        str_new += str[i];
    }
    return str_new;
}

/**
 * Показывает пользователю сообщение, ошибку или предупреждение
 *
 * @param {type} type - тип сообщения (успех, ошибка, предупреждение)
 * @param {type} text - собственно текст сообщения
 * @param {type} lifetime - время жизни сообщения (необязательно)
 */

// Общая переменная для бинда таймаута скрытия сообщения
var message_life;

// Общая переменная для текущего класса сообщения (чтобы можно было почистить)
var current_alert_class = '';
function flash() {
    $.pjax.reload({container: '#site___flash'});
}
/**
 * Показывает сообщение системы
 * @param type - тип сообщения (ошибка, успех или предупреждение)
 * @param text - текст сообщения
 */
function showMessage(type, text) {

    // первым делом чистим таймаут скрытия и
    // немедленно скрываем предыдущее сообщение на всякий случай
    clearTimeout(message_life);
    hideMessage();

    // сообщения могут быть 3-х типов
    // для стилизации сообщения используются классы Twitter Bootstrap
    var types = {
        confirmation: 'alert-info',
        warning: 'alert-warning',
        error: 'alert-danger',
        success: 'alert-success'
    };
    // Записываем текущий класс в общую переменную
    current_alert_class = types[type];

    // Показываем сообщение
    $('#message_body').html(text).addClass(types[type]);
    $('#message_wrapper').show();

    // Ставим таймаут на скрытие сообщения
    message_life = setTimeout(function () {
        hideMessage();
    }, 5000);

    $('#message_wrapper').hover(function () {
        // Убираем таймаут на скрытие, если мышка наведена на сообщение
        clearTimeout(message_life);
    });

    $('#message_wrapper').mouseleave(function () {
        // Ставим таймаут на скрытие сообщения
        message_life = setTimeout(function () {
            hideMessage();
        }, 2000);
    });
}

/**
 * Показывает сообщение системы
 * @param type - тип сообщения (ошибка, успех или предупреждение)
 * @param text - текст сообщения
 */
function message(type, text) {

    // первым делом чистим таймаут скрытия и
    // немедленно скрываем предыдущее сообщение на всякий случай
    clearTimeout(message_life);

    hideMessage();


    // для стилизации сообщения используются классы Twitter Bootstrap
    var types = {
        error: 'alert-danger',
        success: 'alert-success'
    };
    // Записываем текущий класс в общую переменную
    current_alert_class = types[type];

    // Показываем сообщение
    $('#message_body').html(text).addClass(types[type]);
    var message_wrapper = $('#message_wrapper');
    message_wrapper.show();

    // Ставим таймаут на скрытие сообщения
    message_life = setTimeout(function () {
        hideMessage();
    }, 5000);

    message_wrapper.hover(function () {
        // Убираем таймаут на скрытие, если мышка наведена на сообщение
        clearTimeout(message_life);
    });

    message_wrapper.mouseleave(function () {
        // Ставим таймаут на скрытие сообщения
        message_life = setTimeout(function () {
            hideMessage();
        }, 2000);
    });
}

/**
 * loading()
 * показвает или скрывает индикатор загрузки
 * и добавляет в него текст указанный в переменной text
 *
 * Если в переменную text передано false - скрывает индикатор
 * загрузки и очищает текст
 */
function loading(text) {
    if (text === false) {
        unblock();
        //$('#ajax_indicator').hide();
        return true;
    }
    if (!text || text === true) {
        text = 'Загрузка';
    }

    block(text + '...');

    //var ajax_indicator = $('#ajax_indicator');
    //ajax_indicator.html(text + '...').show();
    //var width = parseInt(ajax_indicator.width());
    //ajax_indicator.css({
    //	'margin-left': -(
    //	width / 2
    //	) + 'px'
    //});
    return true;
}

/**
 * Block user interface with message
 * @param text
 */
function block(text) {
    $('.wrap').addClass('blur');
    $('#blocker-screen').show();
    $('#blocker-screen .blocker-screen-text').text(text);
}
/**
 * Unblock user interface
 */
function unblock() {
    $('.wrap').removeClass('blur');
    $('#blocker-screen').hide();
    $('#blocker-screen .blocker-screen-text').text('');
}

/**
 * Скрыватет сообщение с глаз долой
 */
function hideMessage() {
    $('#ajax_indicator').hide();
    $('#message_body').removeClass(current_alert_class);
    $('#message_wrapper').hide();
}


/**
 * Обновляет грид
 *
 * @param {string} grid_id
 * @param {string} [ajax_message=true]
 * @param {object} [data={}]
 */
function updateGrid(grid_id, ajax_message, data) {
    if (!ajax_message) {
        ajax_message = true;
    }

    var ajax_message_timeout = setTimeout(function () {
        loading(ajax_message)
    }, 200);

    $.fn.yiiGridView.update(grid_id, {
        data: data,
        complete: function () {
            clearTimeout(ajax_message_timeout);
            loading(false);
        }
    });
}

/**
 * Make modals responsible
 */
function fixModals() {
    var window_width = parseInt($(window).width());

    var config = [
        {class: 'wide-modal', factor: 1.1},
        {class: 'alittle-wide-modal', factor: 1.5},
        {class: 'middle-modal', factor: 2},
        {class: 'small-modal', factor: 4}
    ];
    for (var i = 0; i < config.length; i++) {
        var modal = config[i];
        var modal_width = window_width / modal.factor;
        var modal_left_margin = modal_width / 2;
        $('.' + modal.class).css({
            'width': modal_width + 'px',
            'margin-left': -modal_left_margin + 'px'
        });
    }
}

$(function () {
    fixModals();
    $(window).resize(function () {
        fixModals();
    });

    /**
     * Скрывает сообщение
     */
    setEvent('click', '#hide_message', function () {
        hideMessage();
    });

    /**
     * Closing modal
     */
    setEvent('click', '.close-modal', function () {
        closeModal(this);
        return false;
    });
});

/**
 * Setting event on some element
 * @param event string
 * @param element string|Jquery object
 * @param callback function
 */
function setEvent(event, element, callback) {
    if (typeof event !== 'string') {
        console.log('setEvent() "callback" parameter must be string ' + typeof event + ' given');
        return false;
    }

    if (typeof element !== 'string') {
        if (typeof element === 'object') {
            if (!element.selector) {
                console.log('setEvent() "element" parameter must be string or Jquery object ' + typeof element + ' given');
                return false;
            }
            element = element.selector;
        }
        else {
            console.log('setEvent() "element" parameter must be string or Jquery object ' + typeof element + ' given');
            return false;
        }
    }

    if (typeof callback !== 'function') {
        console.log('setEvent() "callback" parameter must be function ' + typeof callback + ' given');
        return false;
    }

    $('body').off(event, element).on(event, element, callback);
}

/**
 *
 * @param {string} url
 * @param {string|object} [data]
 * @param {function} [success]
 * @param {function} [error]
 * @param {string} [ajax_message]
 */
function ajax(url, data, success, error, ajax_message) {
    'use strict';

    /**
     * If we pass success as second parameter and
     * ajax_message as third param
     */
    if (typeof data === 'function') {
        var success = data,
            data = {},
            error = null,
            ajax_message = null;
    }
    else if (typeof success === 'string') {
        var ajax_message = success,
            success = null,
            error = null;
    }

    /**
     * Ajax success callback
     * @param res
     */
    var callback = function (res) {
        if (res.status == 200) {
            if (typeof success === 'function') {
                success(res);
            }
            else if (res.message) {
                showMessage('success', res.message);
            }

        }
        else {
            if (typeof error === 'function') {
                error(res);
            }
            console.log(res);
        }
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: url,
        data: data ? data : {},
        beforeSend: function () {
            if (ajax_message) {
                loading(ajax_message);
            }
        },
        complete: function () {
            if (ajax_message) {
                loading(false);
            }
        },
        success: callback,
        error: function (xhr, ajaxOptions, thrownError) {
            if (error) {
                error(xhr.responseText || thrownError);
            }
            console.log(xhr.responseText || thrownError);
        }
    });

    return false;
}


/**
 *
 * @param {string} url
 * @param {string|object} [data]
 * @param {function} [success]
 * @param {function} [error]
 * @param {string} [ajax_message]
 */
function get(url, success, ajax_message) {
    'use strict';

    /**
     * Ajax success callback
     * @param res
     */
    var callback = function (res) {
        if (typeof success === 'function') {
            success(res);
        }
        else if (typeof res.message !== 'undefined') {
            showMessage('success', res.message);
        }
    };

    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: url,
        beforeSend: function () {
            if (ajax_message) {
                loading(ajax_message);
            }
        },
        complete: function () {
            if (ajax_message) {
                loading(false);
            }
        },
        success: callback,
        error: function (xhr, ajaxOptions, thrownError) {
            showMessage('error', thrownError);
        }
    });

    return false;
}


/**
 * Собирает информацию об уже включенных скриптах
 * @returns {Array}
 */
function getIncludedScripts() {
    var data = [];
    $("script[type*=javascript]").each(function () {
        var src = $(this).attr('src');
        if (src && src.length) {
            data.push(src);
        }
    });
    return data;
}

/**
 * Modal right from the backend
 *
 * @param {string} modal_id
 * @param {string} url the action
 * @param {object} [data] data ot the server
 * @param {function} [onSuccess] logic on success
 */
function ajaxModal(modal_id, url, data, onSuccess) {

    if (!data) {
        var data = {};
    }

    if (!modal_id || !url) {
        console.log('ajaxModal() ERROR! Required parameters is missing!');
        return false;
    }

    var wrapper_id = modal_id + '_wrapper';
    var wrapper = $('#' + wrapper_id);
    if (!wrapper.length) {
        $('body').append('<div id="' + wrapper_id + '"></div>');
    }

    loadElement(wrapper_id, url, data, function () {
        fixModals();
        $('#' + modal_id).modal('show');
        if (typeof onSuccess === 'function') {
            onSuccess();
        }
    });
}

/**
 * Loading HTML from the server.
 * Telling to server wich scripts is already included in document
 * @param {string} element_id
 * @param {string} url
 * @param {object} [data]
 * @param {function} [callback]
 * @param {string} [loading_message]
 * @param {boolean} [replace_current_html]
 */
function loadElement(element_id, url, data, callback, loading_message, replace_current_html, not_load_included_scripts) {

    if (!replace_current_html) {
        var replace_current_html = true;
    }

    if (not_load_included_scripts !== false) {
        var not_load_included_scripts = true;
    }

    if (!element_id || !url) {
        console.log('loadElement() ERROR! Required parameters is missing!');
        return false;
    }

    var element = $('#' + element_id);

    if (loading_message && replace_current_html) {
        element.addClass('loading');
        element.html(loading_message + '...');
    }

    //if (not_load_included_scripts) {
    //	if (data) {
    //		data.included_scripts = getIncludedScripts()
    //	}
    //	else {
    //		var data = {
    //			included_scripts: getIncludedScripts()
    //		}
    //	}
    //}

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function (r) {
            // Вот таким вот нехитрым костылем мы определяем пришел ли нам JSON
            if (r[0] === '{') {
                r = JSON.parse(r);
            }

            if (r.message && typeof r.message === 'string') {
                showMessage(r.status == 200 ? 'success' : 'error', r.message);
                if (r.status == 200 && typeof callback === 'function') {
                    callback();
                }
            }
            else {
                element.html(r);
                if (typeof callback === 'function') {
                    callback();
                }
            }
            element.removeClass('loading');
        },
        error: function (r) {
            element.html(r);
            element.removeClass('loading');
        }
    });

    return false;
}


function reloadPjax(id, url) {

    if(!url){
        url = $("#" + id).attr("url");
    }

    $("#" + id).length ? $.pjax.reload({container: "#" + id, "push": 0, "replace": 0, "url":url, async: false}) : false;
}

/**
 * Number checking
 * @param n
 * @returns {boolean}
 */
function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 * Утилиты
 * @constructor
 */
var Utilities = function () {
};

/**
 * == Totally random hash generator ==
 * Function generates random stirng containing characters: 0-9a-zA-Z.
 * Function takes one argument which may be Integer or String.
 * If argument is Integer then argument defines length of generated string.
 * If argument is String then given string is returned but all 'x'
 * characters are replaced with random characters.
 *
 * Usage:
 *
 * hash(5); >>> "XjJfV"
 * hash('xxxx-xx-xxxx-2011');  >>>  "YDFb-5e-cShX-2011"
 *
 * @param {string|number} s Количество символов в хеше и ли паттерн хеша
 * @returns string
 */
Utilities.prototype.hash = function (s) {
    var n;
    if (typeof(
            s
        ) == 'number' && s === parseInt(s, 10)) {
        s = Array(s + 1).join('x');
    }
    return s.replace(/x/g, function () {
        var n = Math.round(Math.random() * 61) + 48;
        n = n > 57 ? (
            n + 7 > 90 ? n + 13 : n + 7
        ) : n;
        return String.fromCharCode(n);
    });
};

/**
 * Гобальный объект с утилитами
 * @type {Utilities}
 */
var U = new Utilities();

/**
 * Приложение
 * @constructor
 */
var App = function () {
    var app = this;
    $(function () {
        app.initEvents();
    });
};

/**
 * Обработчик событий приложения
 */
App.prototype.initEvents = function () {
    var app = this;


    $(document).on("pjax:click", "a.no-pjax", false);


    $(document).on("mouseover", '.qtipped', function () {
        if (!$(this).attr('data-hasqtip')) {
            var show_my = 'top center';
            var show_at = 'bottom center';
            var style = 'qtip-dark';

            if ($(this).attr('data-my')) {
                show_my = $(this).attr('data-my');
            }

            if ($(this).attr('data-at')) {
                show_at = $(this).attr('data-at');
            }

            if ($(this).attr('data-qstyle')) {
                style = $(this).attr('qstyle');
            }

            $(this).qtip({
                style: {
                    classes: style + ' qtip-shadow'
                },
                position: {
                    my: show_my,
                    at: show_at
                }
            }).qtip('toggle', true);
        }
    });

    /**
     * Если у поля есть класс "date_range_input" автоматически
     * инициализируем дейтренж
     */
    setEvent('focus', '.date_range_input', function () {
        app.initDateRange($(this));

    });
    /**
     * Если у поля есть класс "date_range_input" автоматически
     * инициализируем дейтренж
     */
    setEvent('focus', '.time_input', function () {
        $(this).timepicker({
            'minuteStep': 1,
            'secondStep': 5,
            'maxHours': 2,
            'showSeconds': true,
            'defaultTime': '00:05:00',
            'showMeridian': false,
        });
    });
    /**
     * Если у поля есть класс "date_range_input" автоматически
     * инициализируем дейтренж
     */
    setEvent('focus', '.date_picker_input', function () {
        app.initDatePicker($(this));

    });

    /**
     * Скипаем тултипы
     */
    setEvent('click', '.site___tooltip__skip', function () {
        ajax('/site/tooltip/skip?tooltip_id=' + $(this).data('tooltip'));
        $(this).closest('.site___tooltip__wrapper').remove();
    });

    /**
     * Если у поля есть класс "auto_complete_input" автоматически
     * инициализируем автокомплит
     */
    setEvent('focus', '.auto_complete_input', function () {
        app.initAutoComplete($(this));
    });

    /**
     * Если поле с классом "auto_complete_input" было очищено,
     * то очищается и его истинное значение если оно есть рядом
     */
    setEvent('keyup', '.auto_complete_input', function () {
        var sibling = $(".auto_complete_input_real_value[data-input='" + $(this).attr('name') + "']");
        if ($(this).val() == '' && sibling.length) {
            sibling.val('');
        }
    });

    // Every 5 min user refresh session
    //setInterval(function () {
    //    ajax('/site/site/test', {}, function () {
    //    });
    //}, 300000);


    $(document).on("click", ".core___functions__trigger_modal_form_submit", function () {

        var form = $(this).attr('data-id') ? $('#' + $(this).attr('data-id')) : $(this).closest('.modal-dialog').find('form[data-pjax]');

        if ($(this).data('warning') && !confirm($(this).data('warning'))) {
            return false;
        }

        if ($(this).data('add')) {
            var param = $(this).data('add'),
                old_action = form.attr('action'),
                operand = old_action.indexOf('?') === -1 ? '?' : '&',
                new_action = old_action + operand + param;
            form.attr('action', new_action);
        }

        if ($(this).data('replace')) {
            form.attr('action', $(this).data('replace'));
        }

        form.trigger('submit');


        return false;
    });

    $(document).on("click", ".core___functions__delete", function () {
        var item = $(this).closest('[data-key]');

        if ($(this).data('warning') && !confirm($(this).data('warning'))) {
            return false;
        }

        $.ajax({
            type: 'GET',
            url: $(this).attr('href'),
            success: function (data) {
                item.remove();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var res = xhr.responseText || thrownError;
                message('error', JSON.parse(res).message);
            }
        });

        return false;
    });

    $(document).on("click", ".pjax-delete", function () {
        var row = $(this).closest('[data-key]');
        var container = $(this).closest('[data-pjax-container]');

        if ($(this).data('warning') && !confirm($(this).data('warning'))) {
            return false;
        }

        row.hide();

        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: $(this).attr('href'),
            success: function (data) {
                row.remove();
                if (!container.find('[data-key]').length) {
                    reloadPjax(container.attr('id'));
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                message('error', xhr.responseJSON.message);
                row.show();
            }
        });

        return false;
    });

    $(document).on("click", ".pjax-grid-func", function () {

        var btn = $(this);

        var add_container = btn.attr('data-add-container');

        if (btn.hasClass('disabled')) {
            return false;
        }

        btn.addClass('disabled');

        var container = $(this).closest('[data-pjax-container]');

        if ($(this).data('warning') && !confirm($(this).data('warning'))) {
            return false;
        }

        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: $(this).attr('href'),
            success: function (data) {
                reloadPjax(container.attr('id'));
                if (add_container) {
                    reloadPjax(add_container);
                }
                btn.removeClass('disabled');

            },
            error: function (xhr, ajaxOptions, thrownError) {
                message('error', xhr.responseJSON.message);
                btn.removeClass('disabled');
            }
        });

        return false;
    });

    $(document).on("click", "a.pjax-modal", function () {

        app.openPjaxModal($(this).data('container'), $(this).attr('href'));

        return false;
    });
};

/**
 * Генерирует идентификатор для виджета
 */
App.prototype.openPjaxModal = function (container_selector, url) {

    //$('.modal.in').modal('hide');

    if (!container_selector) {
        throw new Error('Необходимо указать атрибут data-container. Например #my_pjax_container. Контейнер должен быть уникальным.');
    }

    var el = $(container_selector);

    if (!el.length) {
        $('body').append('<div id="' + container_selector.replace('#', '') + '"></div>');
    }

    var reload_selector = container_selector;

    var modal = $(container_selector + ' .modal');

    if (modal.length) {
        reload_selector = '#' + modal.find('[data-pjax-container]').attr('id');
    }

    $.pjax.reload(reload_selector, {'url': url, 'push': false, 'replace': false, 'timeout': 10000}).done(function () {
        $(container_selector + ' .modal').modal('show');
    });

    return false;
};

/**
 * Добавление разметки для работы функций блокировки и сообщений
 */
App.prototype.addServiceMarkup = function () {
    var markup = '<div style="display: none;" id="message_wrapper" class="message_wrapper"><div id="message_body" class="message_body alert"></div> </div> <div id="blocker-screen" style="display: none"> <div class="blocker-screen-text"></div> <div class="blocker-screen-backdrop"></div></div>';
    $('body').append(markup);
};


/**
 * Инициализация русского дейтренжа для указанного поля
 * @param {$|string} field Jquery-выборка поля или селектор
 */
App.prototype.initDateRange = function (field) {
    if (typeof field === "string") {
        field = $(field);
    }

    var opens = 'right';
    if (field.data('opens')) {
        opens = field.data('opens');
    }

    var value = field.val();
    var start_date = false;
    var end_date = false;

    if (value) {
        var data = value.split(' - ');
        start_date = data[0];
        end_date = data[1];
    }

    field.daterangepicker({
        format: 'DD.MM.YYYY',
        startDate: start_date,
        endDate: end_date,
        opens: opens,
        'ranges': {
            'за сегодня': [moment().startOf("day"), moment()],
            'за вчера': [moment().startOf("day").subtract("days", 1), moment().subtract("days", 1).endOf("day")],
            'за неделю': [moment().startOf("week"), moment()],
            'за прошлую неделю': [moment().startOf("week").subtract("weeks", 1), moment().subtract("weeks", 1).endOf("week")],
            'за месяц': [moment().startOf("month"), moment()],
            'за прошлый месяц': [moment().startOf("month").subtract("months", 1), moment().subtract("months", 1).endOf("month")],
            'за текущий год': [moment().startOf("year"), moment()]
        },
        locale: {
            'daysOfWeek': ['Вс', 'По', 'Вт', 'Ср', 'Ч', 'Пт', 'Сб'],
            'monthNames': [
                'Январь',
                'Февраль',
                'Март',
                'Апрель',
                'Май',
                'Июнь',
                'Июль',
                'Август',
                'Сентябрь',
                'Октябрь',
                'Ноябрь',
                'Декабрь'
            ],
            applyLabel: 'ОК',
            cancelLabel: 'Отмена',
            fromLabel: 'С',
            toLabel: 'по',
            weekLabel: 'W',
            customRangeLabel: 'Задать период',
            firstDay: 1
        }
    }, function () {
        field.trigger("change");
    });
};

/**
 * Инициализация русского дейтренжа для указанного поля
 * @param {$|string} field Jquery-выборка поля или селектор
 */
App.prototype.initDatePicker = function (field) {
    if (typeof field === "string") {
        field = $(field);
    }
    field.datepicker({dateFormat: 'dd.mm.yy', weekStart: 1});
};

/**
 * Инициализация русского дейтренжа для указанного поля
 * @param {$|string} field Jquery-выборка поля или селектор
 */
App.prototype.initAutoComplete = function (field) {
    if (typeof field === "string") {
        field = $(field);
    }

    var min = field.data('min');

    if (!min) {
        min = 3;
    }

    field.autocomplete({
        'minLength': min, 'select': function (e, ui) {
            var sibling = $(".auto_complete_input_real_value[data-input='" + $(e.target).attr('name') + "']");
            if (ui.item.value && sibling.length) {
                if (ui.item.id) {
                    sibling.val(ui.item.id);
                }
                else {
                    sibling.val(ui.item.value);
                }
            }
            $(e.target).val(ui.item.label);
            $(e.target).trigger('change');
            return false;
        }, 'source': field.data('source')
    });
};

App.prototype.reloadPjax = function (id) {

    var container = $('#' + id);

    if (!container.length) {
        throw  new Error('Container not found!');
    }

    var url = container.attr('data-url');

    if (!url) {
        throw  new Error('Specify URL');
    }

    $.pjax.reload('#' + id, {'url': url, 'push': 0, 'replace': 0, 'timeout': 10000});

};

var app = new App();
app.addServiceMarkup();
