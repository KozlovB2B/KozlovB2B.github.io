/**
 * Хелпер
 */
var SSWidgetHelper = {
    // Получение версии
    getVersion: function () {
        return "1.4.0";
    },
    // Получение пропорции
    getRatio: function () {
        return 1.2;
    },
    // Форматирование строки
    fmt: function (string, hash) {
        var key;
        for (key in hash) string = string.replace(new RegExp('\\{' + key + '\\}', 'gm'), hash[key]);
        return string
    }
};

/**
 * Какими бывают отображения виджета
 * @type {{}}
 */
var SSWidgetViewType = {
    DEFAULT: 1, // Небольшая панель на странице
    MINIMIZED: 2, // Свернут в полоску (без смены ширины и позиции)
    FULLSCREEN: 3 // Развернут на весь экран
};

/**
 * Конструктор виджета
 *
 * @constructor
 */
var SSWidget = function (config) {
    this.checkRequirements();

    this.config = jQuery.extend(true, {}, {
        trace: false,
        fixed: true,
        save_location: true,
        namespace: "sswidget",
        title: "Script Designer",
        messages: {
            fullscreen: 'На весь экран',
            default: 'К обычному состоянию',
            minimize: 'Свернуть',
            close: 'Закрыть'
        },
        template: '<div id="{namespace}-panel">' +
        '<div id="{namespace}-resizable">' +
        '<div id="{namespace}-handler">' +
        '<div id="{namespace}-title">{title}</div>' +
        '<div id="{namespace}-functions">' +
        '<span id="{namespace}-minimize"  title="{msg_minimize}"></span>' +
        '<span id="{namespace}-fullscreen" title="{msg_fullscreen}"></span>' +
        '<span id="{namespace}-default" title="{msg_default}"></span>' +
        '<span id="{namespace}-close"  title="{msg_close}"></span>' +
        '</div>' +
        '</div>' +
        '<iframe id="{namespace}-iframe" src="{location}" marginheight="0" frameborder="0"></iframe>' +
        '</div>' +
        '</div>',
        // Используются бесплатные иконки с сайта iconfinder.com
        images: {
            logo: 'data:image/png;base64,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',
            resizer: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAAGzCI4dAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFxJREFUeNpi+P//PwNAADGACIAAAhNHT1/5DxBAcAYTAxQABBAjWB4JMB07cxUsAqJBGCCAwHpgGKQXUwtMOYyGmwEDAAGGoQUGYAqZ0AWJNgEGWGA6rEy0GbGxAec6VReFEg6PAAAAAElFTkSuQmCC',
            home: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAA5UlEQVQ4T+3TPUoEQRAG0Ldi5CE8g5Gpq6GCd9BVMPUE4g38yUS8xAqG6oKZtzA3M1pQaZjCYZjp7oXdzE67eFTXVz2y5DOq9LbxjfdSfQ24hyl+cIDXHFoCA7trkNMSmgPb2HkDXuEkhw6BfVi89BqTIbQPzGGB3uC4D+2CNVigtzjqom1wESzQlPxXGw1wF49IaUYApZVL9wl8wxb2MQvwHp+4wBhPNVoDpmYOsYGz7gx38Iy1BcGXqO+CqbsElha+PcPU4T/4l8DKZnhZmXJas2wom3jAeiU4b/70x9DaVDrDZb9faT8VA3aEFQAAAABJRU5ErkJggg==',
            default: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAkklEQVQ4T2NkQIBWBgaGKiQ+KcxdDAwM7iANjEi6ljAwMEQyMDBcJsUkBgYGFQYGhicMDAwa2AwMYGBg4CHRwAMMDAwSowaCQw0UKaNhSFQCGk02iGAaTTZEJRmQIvomG1ABex2L254xMDC8ZWBg0MUip8jAwPAYW3lYzsDAUINWisP0X4Rq8sEREFsZGBjCQXIASWAuFe5nAYUAAAAASUVORK5CYII=',
            minimize: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAASklEQVQ4T2NkoDJgpLJ5DKMGUh6io2HIEMHAwKBDYkheYGBgWAPTgx6GgQwMDNokGniRgYFhMy4DSTQLU/loLFMchKOlDeVByAAAsKIEFRjAtYwAAAAASUVORK5CYII=',
            fullscreen: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAABHklEQVQ4T9XUPU7EMBAF4DeT1OBk3HMFOAFwBhAVBdKyBSUtN6Ci2y35EwWCO7BH4ABINFuh2EfwoKwIShEnTqhwaU8+Z0ZPJiPyysARIouAJ+fcaXNciFwQsOgsV/2iUkShesnM711FIYQP7/26OSvLcisw7+UANXuqOlNgc+kGZKLDqqpWsb/s2y+tnUN1CeARwNmfwF9Mdc7Mn0H1bTLYxrz3t9bag8lggxFw7py7q0cyGezCatBYu8+qq1Etx7AaLIpim7LsJBlsYTPn3H00tymxScWScjgGGwR/sAUBdZsPfQE3xuxwnl9HZzgGG4zNWGwQNCLrDLgaarM9gsnBjs3xn4F9D2zqGxlC2AXRDRmRFwaOUz/srSN6/gZjrvdiMzgouAAAAABJRU5ErkJggg==',
            close: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAABNklEQVQ4T7WUTU4CQRCFX80JnKph6V4lMR7IqNEImvhzGAKoAeKB3BGNrt3SNWs3PDNRDMhMz5hg7zpd/XW9elUt2PCSDfPw78AkTdN2nufTJpmr6ra7vy/HrmRoZrsEpgSu8xCGMWhqdijAYyLSns1mb4vYNcmqegqRhxjUzI4ITAS4DSH0KzNcHMSgZnZMYFwGK+5XmlIGNbMTAiMCN3kIg7KSRF1ehgr5UVeKaIa/5Rf7JmbV9qGqnkHkvigPgas69+OSs+wc5BDkBZJEQN7VQatNybIOyEEBc/dRIVm/HohCS4FplnWF7IPsuvt42c066BowNbsUoAey4+6TstaIQVeAW63WQTKfP8VgP+5/ywe57+7PVaOXqOqOu780/Bz23P0VwLxylpuAYjG1ffjXBzYO/ATSKakVVcom5wAAAABJRU5ErkJggg=='
        },
        afterDestroy: null, // Callback вызываемый после уничтожения
        afterInit: null, // Callback вызываемый после инициализации виджета
        default_state: {
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
        min_size: {
            width: 265,
            height: Math.round(265 * SSWidgetHelper.getRatio())
        }
    }, config);

    this.init();
};

/**
 * Проверка наличия зависимостей
 */
SSWidget.prototype.checkRequirements = function () {
    if (typeof jQuery === 'undefined') {
        throw new Error('Необходимо подключить jQuery')
    } else if (typeof jQuery.ui === 'undefined') {
        throw new Error('Необходимо подключить jQuery UI')
    }
};


/**
 * Инициализация виджета
 */
SSWidget.prototype.init = function () {
    this.trace = this.config.trace === true ? console.log : function () {
    };

    if (!jQuery('#' + this.config.namespace + '-loaded').length) {
        jQuery('body').append('<div id="' + this.config.namespace + '-loaded"></div>');
    }

    this.trace('Инициализация виджета');
    this.loadState();
    this.state.destroyed = false;
    this.render();
    this.setStyles();
    this.applyStateView();
    this.setEvents();
    this.setWatchers();
    this.iframeHeight();
    this.writeState();

    if (typeof this.config.afterInit === 'function') {
        this.config.afterInit.call(this);
    }
};

/**
 * Отображение представления виджета записаного в state
 */
SSWidget.prototype.applyStateView = function () {
    this.trace('Отображаем виджет таким, каким он был записан в state');

    switch (this.state.view) {
        case SSWidgetViewType.MINIMIZED:
            this.minimize();
            break;
        case SSWidgetViewType.FULLSCREEN:
            this.fullScreen();
            break;
        case SSWidgetViewType.DEFAULT:
            this.defaultScreen();
            break;
        default :
            throw new Error('Неизвестный тип представления виджета!');
            break;
    }
};

/**
 * Загрузка состояния
 */
SSWidget.prototype.loadState = function () {
    var from_storage = localStorage.getItem(this.config.namespace);

    if (from_storage) {
        this.state = JSON.parse(from_storage);
        this.trace('Загрузка состояния из localStorage: ', this.state);
    } else {
        this.state = this.config.default_state;
        this.trace('Загрузка состояния из по-умолчанию: ', this.state);
    }
};

/**
 * Запись состояния
 */
SSWidget.prototype.writeState = function () {
    this.trace('Запись состояния в localStorage: ', this.state);
    localStorage.setItem(this.config.namespace, JSON.stringify(this.state));
};

/**
 * Сброс виджета в дефолтное состояние
 */
SSWidget.prototype.flush = function () {
    this.trace('Сброс виджета в состояние по-умолчанию');
    localStorage.removeItem(this.config.namespace);
    this.state = this.config.default_state;
    this.destroy();
    this.init();
};

/**
 * Внедрение HTML панели в текущую страницу
 */
SSWidget.prototype.render = function () {
    this.trace('Добавление элемента виджета - #' + this.config.namespace + '-panel');

    var exist = jQuery('#' + this.config.namespace + '-panel');

    if (exist.length) {
        exist.remove();
    }

    this.panel = jQuery(SSWidgetHelper.fmt(this.config.template, {
        title: this.config.title,
        namespace: this.config.namespace,
        location: this.state.location,
        msg_fullscreen: this.config.messages.fullscreen,
        msg_default: this.config.messages.default,
        msg_minimize: this.config.messages.minimize,
        msg_close: this.config.messages.close
    }));

    jQuery('body').append(this.panel);

    this.initInteractions();
};


/**
 * Возвращаем элемент в position fixed
 */
SSWidget.prototype.toFixed = function (ui) {
    if (this.config.fixed) {
        this.trace('Возвращаем элемент в position fixed');
        var top = ui.helper.offset().top - jQuery(window).scrollTop();
        if (top < 0) {
            top = 0;
        }

        ui.helper.css('position', 'fixed');
        ui.helper.css('top', top + "px");
    }
};

/**
 * Инициализация draggable и resizable
 */
SSWidget.prototype.initInteractions = function () {
    this.trace('Инициализация draggable и resizable');

    var widget = this;

    jQuery('#' + this.config.namespace + '-resizable').resizable({
        handles: "e, s, se",
        minHeight: this.config.min_size.height,
        minWidth: this.config.min_size.width,
        start: function () {
            widget.interactionOverlay();
        },
        stop: function (event, ui) {
            widget.state.size.width = parseInt(ui.helper.css('width'));
            widget.state.size.height = parseInt(ui.helper.css('height'));
            widget.writeState();
            widget.removeInteractionOverlay();
        }
    });

    this.panel.draggable({
        cursor: "move",
        handle: "#" + this.config.namespace + '-handler',
        containment: "body",
        start: function (event, ui) {
            widget.interactionOverlay();
        },
        stop: function (event, ui) {
            widget.toFixed(ui);
            widget.state.position.top = parseInt(widget.panel.css('top'));
            widget.state.position.left = parseInt(widget.panel.css('left'));
            widget.writeState();
            widget.removeInteractionOverlay();
        },
        scroll: false
    });
};

/**
 * Обычное отображение виджета
 */
SSWidget.prototype.defaultScreen = function () {
    this.trace('Обычное отображение виджета');

    // Корректировка бага когда виджет улетал за пределы окна
    // и нельзя было его вернуть перетаскиванием
    if (this.state.position.top < 0) {
        this.state.position.top = 0;
        this.writeState();
    }

    if (this.state.view != SSWidgetViewType.DEFAULT) {
        this.state.view = SSWidgetViewType.DEFAULT;
        this.writeState();
    }

    jQuery('#' + this.config.namespace + '-fullscreen').show();
    jQuery('#' + this.config.namespace + '-minimize').show();
    jQuery('#' + this.config.namespace + '-default').hide();
    jQuery('#' + this.config.namespace + '-iframe').show();



    jQuery('#' + this.config.namespace + '-resizable').css({
        'width': this.state.size.width + 'px',
        'height': this.state.size.height + 'px'
    }).resizable("enable");

    this.setPosition();

    this.panel.find(".ui-resizable-handle").show();
    this.panel.draggable("enable");
    jQuery('#' + this.config.namespace + '-handler').css({cursor: 'move'});

    this.iframeHeight();
};

/**
 * Установка позиции виджета
 */
SSWidget.prototype.setPosition = function () {
    this.panel.css({
        'position': 'fixed',
        'top': this.state.position.top + 'px'
    });

    if (this.state.position.left === -1) {
        this.panel.css({
            'left': (jQuery('body').innerWidth() - this.panel.outerWidth()) + 'px'
        });
    } else {
        this.panel.css({
            'left': this.state.position.left + 'px'
        });
    }
};

/**
 * Минимизация виджета
 */
SSWidget.prototype.minimize = function () {
    this.trace('Свернуть виджет');

    if (this.state.view != SSWidgetViewType.MINIMIZED) {
        this.state.view = SSWidgetViewType.MINIMIZED;
        this.writeState();
    }

    jQuery('#' + this.config.namespace + '-fullscreen').hide();
    jQuery('#' + this.config.namespace + '-minimize').hide();
    jQuery('#' + this.config.namespace + '-default').show();
    jQuery('#' + this.config.namespace + '-iframe').hide();

    jQuery('#' + this.config.namespace + '-resizable').resizable("disable").css({
        height: jQuery('#' + this.config.namespace + '-handler').outerHeight() + 'px',
        width: this.state.size.width + 'px'
    });

    this.setPosition();

    this.panel.find(".ui-resizable-handle").hide();
};

/**
 * Открыть виджет на весь экран
 */
SSWidget.prototype.fullScreen = function () {
    this.trace('Открыть на весь экран');

    if (this.state.view != SSWidgetViewType.FULLSCREEN) {
        this.state.view = SSWidgetViewType.FULLSCREEN;
        this.writeState();
    }
    jQuery('#' + this.config.namespace + '-iframe').show();
    jQuery('#' + this.config.namespace + '-fullscreen').hide();
    jQuery('#' + this.config.namespace + '-minimize').hide();
    jQuery('#' + this.config.namespace + '-default').show();

    jQuery('#' + this.config.namespace + '-resizable').resizable("disable").css({
        'width': jQuery(window).innerWidth(),
        'height': jQuery(window).innerHeight()
    });

    this.panel.find(".ui-resizable-handle").hide();

    this.panel.draggable("disable");

    jQuery('#' + this.config.namespace + '-handler').css({cursor: 'default'});

    this.panel.css({
        'position': 'fixed',
        'top': 0,
        'left': 0
    });


    this.iframeHeight();
};

/**
 * Подстройка высоты iframe
 */
SSWidget.prototype.iframeHeight = function () {
    this.trace('Подстройка высоты iframe');

    var height = this.panel.height() - jQuery('#' + this.config.namespace + '-handler').outerHeight();

    jQuery('#' + this.config.namespace + '-iframe').css({
        'height': height + 'px'
    });
};

/**
 * Отправка сообщения для iframe
 * @param data
 */
SSWidget.prototype.sendData = function (data) {
    this.trace('Отправка сообщения для iframe: ', data);
    document.getElementById(this.config.namespace + '-iframe').contentWindow.postMessage(JSON.stringify(data), "*");
};

/**
 * Заргужаем iframe
 *
 * @param url
 */
SSWidget.prototype.changeLocation = function (url) {
    this.trace('Меняем location irame: ', url);
    jQuery('#' + this.config.namespace + '-iframe').attr('src', url);
};

/**
 * Устанавливем стили напрямую в элементы чтобы не подгружать css таблиц
 */
SSWidget.prototype.setStyles = function () {
    this.trace('Установка стилей элементов');

    this.panel.css({
        'box-sizing': 'border-box',
        'z-index': 2147483647,
        'border-radius': '2px',
        'position': 'fixed',
        'border': 'none',
        'margin': 0,
        'padding': 0,
        'box-shadow': '0 0 10px #888888',
        'background-color': '#eeeeee',
        'overflow': 'hidden'
    });

    jQuery('#' + this.config.namespace + '-resizable').css({
        'overflow': 'hidden'
    });

    jQuery('#' + this.config.namespace + '-handler').css({
        'overflow': 'hidden',
        'cursor': 'move'
    });

    jQuery('#' + this.config.namespace + '-iframe').css({
        'width': '100%',
        'border-bottom-left-radius': '2px',
        'border-bottom-right-radius': '2px'
    });

    jQuery('#' + this.config.namespace + '-title').css({
        'float': 'left',
        'padding': '6px 6px 6px 32px',
        'margin': '0',
        'font-family': 'Arial, Helvetica, sans-serif',
        'color': '#000000',
        'font-weight': 'bold',
        'font-size': '14px',
        'background': 'transparent url(' + this.config.images.logo + ') 4px 4px no-repeat'
    });

    jQuery('#' + this.config.namespace + '-functions').css({
        'float': 'right',
        'margin': '0',
        'padding': '0'
    });

    var function_button_css = {
        'display': 'inline-block',
        'list-style': 'none',
        'margin': '4px 0 0 4px',
        'width': '20px',
        'height': '20px',
        'cursor': 'pointer'
    };

    jQuery('#' + this.config.namespace + '-minimize')
        .css(function_button_css)
        .css({'background': 'transparent url(' + this.config.images.minimize + ') 0 0 no-repeat'});

    jQuery('#' + this.config.namespace + '-close')
        .css(function_button_css)
        .css({'background': 'transparent url(' + this.config.images.close + ') 0 0 no-repeat'});

    jQuery('#' + this.config.namespace + '-default')
        .css(function_button_css)
        .css({
            'background': 'transparent url(' + this.config.images.default + ') 0 0 no-repeat',
            'background-size': '16px',
            'background-position-y': '2px'
        });

    jQuery('#' + this.config.namespace + '-fullscreen')
        .css(function_button_css)
        .css({
            'background': 'transparent url(' + this.config.images.fullscreen + ') 0 0 no-repeat',
            'background-size': '16px',
            'background-position-y': '2px'
        });

    this.panel.find('.ui-resizable-handle').css({
        'position': 'absolute',
        'font-size': '0.1px',
        'display': 'block',
        '-ms-touch-action': 'none',
        'touch-action': 'none'
    });

    this.panel.find('.ui-resizable-e').css({
        'cursor': 'e-resize',
        'width': '12px',
        'right': '-6px',
        'top': '0',
        'height': '100%'
    });

    this.panel.find('.ui-resizable-s').css({
        'cursor': 's-resize',
        'width': '100%',
        'left': '0',
        'bottom': '-6px',
        'height': '12px'
    });

    this.panel.find('.ui-resizable-se').css({
        'cursor': 'se-resize',
        'background': 'transparent url(' + this.config.images.resizer + ') 0 0 no-repeat',
        'right': 0,
        'bottom': 0,
        'width': '12px',
        'height': '12px'
    });
};

/**
 * Оверлей над iframe для избежания багов при перемещении или изменении размеров
 */
SSWidget.prototype.interactionOverlay = function () {
    this.trace('Оверлея iframe для предотвращения багов при изменении размера и перемещении');

    this.interaction_overlay = jQuery('<div></div>').css({
        position: 'absolute',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%'
    });

    this.panel.append(this.interaction_overlay);
};

/**
 * Убрать оверлей iframe
 */
SSWidget.prototype.removeInteractionOverlay = function () {
    this.trace('Убрать оверлей iframe');
    this.interaction_overlay.remove();
};

/**
 * Уничтожение виджета
 */
SSWidget.prototype.destroy = function () {
    this.trace('Уничтожение виджета');

    this.destroyEvents();
    this.destroyWatchers();
    this.panel.draggable('destroy');
    jQuery('#' + this.config.namespace + '-resizable').resizable('destroy');
    this.panel.remove();
    this.state.destroyed = true;
    this.writeState();

    if (typeof this.config.afterDestroy === 'function') {
        this.trace('Выполнение кода после уничтожения');
        this.config.afterDestroy.call(this);
    }
};

/**
 * Уничтожение наблюдателей
 */
SSWidget.prototype.destroyWatchers = function () {
    this.trace('Уничтожение наблюдателей');

    if (this.page_watcher_loop) {
        clearInterval(this.page_watcher_loop)
    }
};

/**
 * Установка наблюдателей
 */
SSWidget.prototype.setWatchers = function () {
    var widget = this;
    this.trace('Инициализация наблюдателей');

    var oldLocation = window.location.href;

    if (this.page_watcher_loop) {
        clearInterval(this.page_watcher_loop)
    }

    this.page_watcher_loop = setInterval(function () {
        if (window.location.href != oldLocation) {
            oldLocation = window.location.href;
            widget.sendData({page: oldLocation});
        }
    }, 500);

    document.getElementById(this.config.namespace + '-iframe').onload = function () {
        widget.sendData({page: oldLocation});
    };
};

/**
 * Инициализация обработчиков событий
 */
SSWidget.prototype.setEvents = function () {
    var widget = this;

    this.trace('Инициализация обработчиков событий');

    var body = jQuery('body');

    body.on('click', '#' + this.config.namespace + '-close', function () {
        widget.destroy();
    });

    body.on('click', '#' + this.config.namespace + '-minimize', function () {
        widget.minimize();
    });

    body.on('click', '#' + this.config.namespace + '-default', function () {
        widget.defaultScreen();
    });

    body.on('click', '#' + this.config.namespace + '-fullscreen', function () {
        widget.fullScreen();
    });

    body.on('resize', '#' + this.panel.attr('id'), function () {
        widget.iframeHeight();
    });

    jQuery(window).on("message onmessage", function (e) {
        var data = e.originalEvent.data;
        var parsed = null;
        if (typeof data === 'string') {
            parsed = JSON.parse(data);
        } else {
            parsed = data;
        }

        if (widget.config.save_location && typeof data.location !== 'undefined') {
            widget.state.location = data.location;
            widget.writeState();
        }
    });

    //jQuery(window).resize(function () {
    //    this.trace('Подстройка высоты iframe в при изменении размера');
    //    widget.iframeHeight();
    //});
};


/**
 * Удаление обработчиков событий
 */
SSWidget.prototype.destroyEvents = function () {
    this.trace('Удаление обработчиков событий');
    var body = jQuery('body');
    body.off('click', '#' + this.config.namespace + '-close');
    body.off('click', '#' + this.config.namespace + '-minimize');
    body.off('click', '#' + this.config.namespace + '-default');
    body.off('click', '#' + this.config.namespace + '-fullscreen');
    body.off('resize', '#' + this.panel.attr('id'));
    jQuery(window).off('message onmessage');
};


if (window['SSWidgetInstance']) {
    var SSWidgetLoadRequirementsQueued = {};

    /**
     * Подгрузка зависимостей
     */
    function SSWidgetLoadRequirements(name, src) {

        if (SSWidgetLoadRequirementsQueued[name]) {
            return;
        }

        SSWidgetLoadRequirementsQueued[name] = true;

        (function (d, w) {
            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () {
                    n.parentNode.insertBefore(s, n);
                };
            s.type = "text/javascript";
            s.src = src;
            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window);
    }

    window['SSWidgetjQueryIsReadyInterval'] = setInterval(function () {
        if (typeof jQuery !== "undefined") {
            if (typeof jQuery.ui !== "undefined") {

                clearInterval(window['SSWidgetjQueryIsReadyInterval']);

                window[window['SSWidgetInstance']] = new SSWidget(window['SSWidgetConfig'] ? window['SSWidgetConfig'] : {});
            } else {
                SSWidgetLoadRequirements('jquery', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
            }
        } else {
            SSWidgetLoadRequirements('jqueryui', 'https://code.jquery.com/jquery-3.1.1.min.js');
        }
    }, 500);
}
