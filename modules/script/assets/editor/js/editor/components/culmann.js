/**
 * Четрежная доска
 *
 * @param config
 * @constructor
 */
var Culmann = function (config) {

    /**
     * @type {*|jQuery|HTMLElement}
     */
    this.container;

    /**
     * @type {*|jQuery|HTMLElement}
     */
    this.coordinator;

    /**
     * @type {*|jQuery|HTMLElement}
     */
    this.paper;

    /**
     * @type  {jQuery|mixed}
     */
    this.panzoom;

    /**
     * @type {integer}
     */
    this.node_width = 200;

    /**
     * @type {integer}
     */
    this.node_height = 200;

    /**
     * @type {integer}
     */
    this.paper_size = 20000;

    /**
     * @type {integer}
     */
    this.snap_grid_size = 5;

    /**
     * @type {integer}
     */
    this.birdseye_grid_size = 100;

    /**
     * @type {integer}
     */
    this.min_zoom = 0.1;

    /**
     * @type {integer}
     */
    this.max_zoom = 4;

    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Culmann.prototype = Object.create(YiijBaseModel.prototype);
Culmann.prototype.constructor = Culmann;

/**
 * @inheritdoc
 */
Culmann.prototype.requiredConfig = function () {
    return ['container', 'coordinator'];
};

/**
 * @inheritdoc
 */
Culmann.prototype.zoomCorrection = function (position) {
    var matrix = this.getMatrix();

    position.left = position.left / matrix.x_scale;
    position.top = position.top / matrix.x_scale;
};


/**
 * @inheritdoc
 */
Culmann.prototype.snapToGrid = function (position) {
    position.top = position.top - (position.top % this.snap_grid_size);
    position.left = position.left - (position.left % this.snap_grid_size);

    return position;
};

/**
 * @inheritdoc
 */
Culmann.prototype.getCoordinatorProjection = function (position) {

    var matrix = this.getMatrix();

    var coordinator_offset_y = parseInt(this.coordinator.css('top')) + parseInt(this.paper.css('top'));
    var coordinator_offset_x = parseInt(this.coordinator.css('left')) + parseInt(this.paper.css('left'));

    var coordinator_y = (position.top - coordinator_offset_y - matrix.y_offset) / matrix.y_scale;
    var coordinator_x = (position.left - coordinator_offset_x - matrix.x_offset) / matrix.x_scale;

    return {
        'top': coordinator_y,
        'left': coordinator_x
    };
};

/**
 * @inheritdoc
 */
Culmann.prototype.showNewObject = function (position, text) {
    $('#new-object').show().text(text).css(position);
};

/**
 * @inheritdoc
 */
Culmann.prototype.hideNewObject = function (position, text) {
    $('#new-object').hide();
};

/**
 * @inheritdoc
 */
Culmann.prototype.showRulers = function (position) {
    $('#ruler-x').show().css({
        'width': this.paper_size,
        'top': position.top,
        'left': position.left - (this.paper_size / 2)
    });

    $('#ruler-y').show().css({
        'height': this.paper_size,
        'top': position.top - (this.paper_size / 2),
        'left': position.left
    });
};

/**
 * @inheritdoc
 */
Culmann.prototype.hideRulers = function () {
    $('#ruler-x').hide();
    $('#ruler-y').hide();
};

/**
 * Инициализация объекта куратора WS соединения
 */
Culmann.prototype.init = function () {

    Yiij.trace('Инициализация чертежной доски');

    this.loadDomElements();
    this.initPan();
};

/**
 * Загружает DOM элементы
 */
Culmann.prototype.loadDomElements = function () {
    this.container = $('#' + this.container);
    this.paper = $('#' + this.paper);
    this.coordinator = $('#' + this.coordinator);
};


/**
 * Текущее состояние матрицы преобразования контейнера.
 *
 * transform: matrix(a, c, b, d, tx, ty)
 *
 * a (x_scale) Изменение размера по горизонтали    Изменение масштаба по горизонтали. Значение больше 1 расширяет элемент, меньше 1, наоборот, сжимает.
 * b (x_tilt) Наклон по вертикали    Наклон по горизонтали. Положительное значение наклоняет влево, отрицательное вправо.
 * c (y_tilt) Наклон по горизонтали    Наклон по вертикали. Положительное значение наклоняет вверх, отрицательное вниз.
 * d (y_scale) Изменение размера по вертикали    Изменение масштаба по вертикали. Значение больше 1 расширяет элемент, меньше 1 — сжимает.
 * tx (x_offset) Смещение по горизонтали в пикселах    Смещение по горизонтали в пикселах. Положительное значение сдвигает элемент вправо на заданное число пикселов, отрицательное значение сдвигает влево.
 * ty (y_offset) Смещение по вертикали в пикселах    Смещение по вертикали в пикселах. При положительном значении элемент опускается на заданное число пикселов вниз или вверх при отрицательном значении.
 *
 * См. http://htmlbook.ru/blog/matritsa-preobrazovanii
 */
Culmann.prototype.getMatrix = function () {

    var matrix = this.container.panzoom("getMatrix");


    return {
        'x_scale': matrix[0],
        'x_tilt': matrix[1],
        'y_tilt': matrix[2],
        'y_scale': matrix[3],
        'x_offset': matrix[4],
        'y_offset': matrix[5]
    };

    //var members_map = ['x_scale', 'x_tilt', 'y_tilt', 'y_scale', 'x_offset', 'y_offset'];
    //
    //var transform = this.container.css('transform');
    //
    //if (transform && transform !== 'none') {
    //
    //    var values = transform.replace(/matrix\((.*)\)/, '$1').split(',');
    //
    //    for (var i = 0; i < values.length; i++) {
    //        if (typeof state[members_map[i]] !== 'undefined') {
    //            state[members_map[i]] = parseFloat(values[i]);
    //        }
    //    }
    //}

    //return state;
};

/**
 *
 * @returns {{w: number, h: number}}
 */
Culmann.prototype.screen = function () {
    return {
        'w': Math.max(document.documentElement.clientWidth, window.innerWidth || 0),
        'h': Math.max(document.documentElement.clientHeight, window.innerHeight || 0)
    };
};

/**
 * Фокусирует внимание на точке координатора
 *
 * @param {Node} node
 */
Culmann.prototype.focus = function (node) {

    if (typeof node == 'number') {

        node = Yiij.app.getModule('editor').nodeController.findByNumber(node);

        if (!node) {
            return false;
        }
    }

    this.container.panzoom("setMatrix", [2, 0, 0, 2, ((node.left + (this.node_width / 2)) * -2 ), ((node.top + (this.node_height / 2)) * -2)]);
};

/**
 * Инициализация поведения - pan
 */
Culmann.prototype.initPan = function () {
    var cul = this;

    this.paper.css({
        'height': this.paper_size,
        'width': this.paper_size,
        'top': -1 * (this.paper_size / 2),
        'left': -1 * (this.paper_size / 2)
    });


    var screen = this.screen();


    var coordinator_top = (this.paper_size / 2) + (screen.h / 2);

    var coordinator_left = (this.paper_size / 2) + (screen.w / 2);

    this.coordinator.css({
        'top': coordinator_top - (coordinator_top % this.birdseye_grid_size),
        'left': coordinator_left - (coordinator_left % this.birdseye_grid_size)
    });


    this.panzoom = this.container.panzoom({
        cursor: 'grab',
        animate: false,
        ignoreChildrensEvents: true,
        butDoNotIgnoreThis: this.paper.attr('id'),
        increment: 0.1,
        minScale: this.min_zoom,
        maxScale: this.max_zoom,
        onChange: function (e) {
            cul.disableOrEnableControls();
            cul.enableOrDisableOverview();
        }
    });

    this.panzoom.parent().on('mousewheel.focal', function (e) {
        e.preventDefault();
        var delta = e.delta || e.originalEvent.wheelDelta;
        var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;

        cul.panzoom.panzoom('zoom', zoomOut, {
            focal: e
        });
    });
};

/**
 *
 * @param zoomIn
 */
Culmann.prototype.zoom = function (zoomIn) {
    this.panzoom.panzoom('zoom', !zoomIn, {
        focal: {
            clientX: parseInt(this.container.css('width')) / 2,
            clientY: parseInt(this.container.css('height')) / 2
        }
    });
};

/**
 *
 * @param zoomIn
 */
Culmann.prototype.disableOrEnableControls = function (zoomIn) {
    var zoom = this.container.panzoom("getMatrix")[0];
    var panel = Yiij.app.getModule('editor').panel;

    if (zoom < this.max_zoom) {
        panel.enableButton('zoom_in');
    } else {
        panel.disableButton('zoom_in');
    }

    if (zoom > this.min_zoom) {
        panel.enableButton('zoom_out');
    } else {
        panel.disableButton('zoom_out');
    }
};

/**
 *
 * @param zoomIn
 */
Culmann.prototype.enableOrDisableOverview = function (zoomIn) {
    var zoom = this.container.panzoom("getMatrix")[0];

    if (zoom < 0.5) {
        this.paper.attr('data-overview', 'birdseye');
        this.paper.attr('data-show-numbers', 'show');
    } else if (zoom < 0.7) {
        this.paper.attr('data-overview', 'high');
        this.paper.attr('data-show-numbers', 'show');
    } else if (zoom < 1) {
        this.paper.attr('data-overview', 'high');
        this.paper.attr('data-show-numbers', 'hide');
    } else {
        this.paper.attr('data-overview', 'normal');
        this.paper.attr('data-show-numbers', 'hide');
    }
};

/**
 * Возвращает квадратную область в сисеме координат, которую занимают все узлы
 *
 * @returns {{left: 0, right: 0, top: 0, bottom: 0, width: 0, height: 0}}
 */
Culmann.prototype.getObjectsArea = function (padding) {

    if (!padding) {
        padding = 0;
    }

    var area = {
        left: null,
        right: null,
        top: null,
        bottom: null,
        width: null,
        height: null
    };

    // Находим координаты вершин прямоугольника области размещения узлов
    this.coordinator.find('.node, .group').each(function () {
        if (!$(this).is(':hidden')) {
            var node_top = parseInt($(this).css('top'));
            var node_left = parseInt($(this).css('left'));
            var node_width = $(this).outerWidth();
            var node_height = $(this).outerHeight();
            var node_bottom = node_top + node_height;
            var node_right = node_left + node_width;

            if (area.top === null || node_top < area.top) {
                area.top = node_top;
            }

            if (area.bottom === null || node_bottom > area.bottom) {
                area.bottom = node_bottom;
            }

            if (area.left === null || node_left < area.left) {
                area.left = node_left;
            }

            if (area.right === null || node_right > area.right) {
                area.right = node_right;
            }
        }
    });

    for (var i in area) {
        if (area.hasOwnProperty(i) && area[i] === null)
            area[i] = 0;
    }

    if (padding > 0) {
        area.top -= padding;
        area.bottom += padding;
        area.left -= padding;
        area.right += padding;
    }

    // Находим ширину и длину области размещения узлов
    area.width = Math.abs(area.left - area.right);
    area.height = Math.abs(area.top - area.bottom);

    //$('#area-visualization').css(area).show();


    return area;
};

/**
 * Изменяет зум и центр так, чтобы весь скрипт уместился в экран
 */
Culmann.prototype.fit = function () {
    // Отступ от края
    var padding = 200;
    var area = this.getObjectsArea(padding);

    // Находимм центральную точку области размещения узлов
    var x = area.left + (area.width / 2);
    var y = area.top + (area.height / 2);

    // Вычисляем необходимый зум исходя из соотношения сторон видимой области и области размещения узлов
    var z1 = parseInt(this.container.css('width')) / area.width;

    var z2 = parseInt(this.container.css('height')) / area.height;

    var zoom = (z1 < z2 ? z1 : z2).toFixed(1);

    this.container.panzoom("setMatrix", [zoom, 0, 0, zoom, x * -zoom, y * -zoom]);
};