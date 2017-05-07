/**
 * Для того чтобы отрисовать все связи при перетаскивании узла
 * необходимо обновлять только те связи, которые исходят из узла или входят в него.
 *
 * Для этого все связи нужно хранить в хеш таблице, где ключами будет ID связи (пара id варианта - id узла назначения)
 * а значением объект с вычисленными данными для отрисовки SVG элемента.
 * Хранить их нужно чтобы не вычислять повторно при каждом событии перемещения узла.
 *
 * Так же нужно иметь индекс, где ключами будут ID узлов а значениями массивы с ID связей.
 *
 * Таким образом при перемещении узла мы обращаемся к индексу по узлам и проходим в цикле и обновляем только
 * те связи, которые относятся к этому узлу.
 *
 * Индекс формируется при изменении данных вариантов или узлов. При каждом удалении узла или варианта а так же при смене
 * ссылки у варианта индекс обновляется.
 */

var RelationManager = function () {
    this.renderer = jsPlumb.getInstance({
        PaintStyle: {
            strokeWidth: 2,
            lineWidth: 2,
            strokeStyle: "#f76258",
            joinstyle: "round"
        },
        Connector: ["Flowchart",
            {
                gap: 0,
                cornerRadius: 2,
                stub: 25
            }
        ],
        Overlays: [["Arrow", {location: 1, width: 10, length: 10}]],
        Anchors: [["Left", "Right"], ["Left", "Right", "Top"]],
        Container: Yiij.app.getModule('editor').culmann.coordinator
    });


    this.renderer.bind("connection", function (info) {

        var $target = $(info.target);
        var $source = $(info.source);

        var variant_id = $source.attr('id');
        var node_id = $target.attr('id');

        if ($target.hasClass('node')) {
            Yiij.app.getModule('editor').relationsManager.renderer.detach(info.connection);

            if ($source.hasClass('variant')) {

                var variant = Yiij.app.getModule('editor').variantController.get(variant_id);

                // Вариант не может вести на родительский узел
                if (variant.node_id !== node_id) {
                    Yiij.app.getModule('editor').create({
                        'model_class': 'Variant',
                        'model_id': variant_id,
                        'p': {
                            'target_id': node_id
                        },
                        'r': {
                            'target_id': variant.target_id
                        }
                    });
                }
            } else if ($source.hasClass('group-variant')) {
                Yiij.app.getModule('editor').create({
                    'model_class': 'GroupVariant',
                    'model_id': variant_id,
                    'p': {
                        'target_id': node_id
                    },
                    'r': {
                        'target_id': Yiij.app.getModule('editor').groupvariantController.get(variant_id).target_id
                    }
                });
            }
        }
    });
};

/**
 *
 */
RelationManager.prototype.recreateAll = function () {

    Yiij.trace('Повторное создание всех связей');

    for (var i in this.relations) {
        var variant = this.relations[i].variant;

        this.remove(variant);

        this.relations[variant.id] = new Relation({
            'id': variant.id,
            'variant': variant
        });

        this.relations[variant.id].render();
    }
};
/**
 * Создание связи
 *
 * @param {Variant} variant
 */
RelationManager.prototype.create = function (variant) {

    if (this.relations[variant.id] && this.relations[variant.id].variant.target_id != variant.target_id) {
        Yiij.trace('Удаление существующей связи потому что поменялся target у  ' + variant.id + ' - старый target ' + this.relations[variant.id].variant.target_id + '- новый target ' + variant.target_id);

        this.remove(variant);
    }


    if (!this.relations[variant.id]) {
        Yiij.trace('Создание связи для варианта  ' + variant.id + ' - target ' + variant.target_id);

        this.relations[variant.id] = new Relation({
            'id': variant.id,
            'variant': variant
        });

        this.relations[variant.id].render();
    } else {
        Yiij.trace('Визуальная связь для варианта  ' + variant.id + ' не нуждается в обновлении.');
    }
};

/**
 * Создание связи
 *
 * @param {string} id
 * @param {boolean} hide
 */
RelationManager.prototype.showHide = function (id, hide) {

    var hide_relation = !!hide;
    var action = (hide_relation ? 'Скрываю' : 'Отображаю');

    for (var i in this.relations) {

        var relation = this.relations[i];

        var need_change = relation.variant.target_id == id;

        if (need_change) {
            Yiij.trace(action + ' представление связи потому что она указывает на узел ' + id);
        }

        if (!need_change) {
            if (relation.variant instanceof Variant) {
                need_change = relation.variant.node_id == id;
            } else if (relation.variant instanceof GroupVariant) {
                need_change = relation.variant.group_id == id;
            } else {
                throw new Error('Неизвестный класс варианта');
            }

            if (need_change) {
                Yiij.trace(action + ' представление связи потому что исходит из узла ' + id);
            }
        }

        if (need_change) {
            relation.hidden = hide_relation;
            relation.render();
        }
    }
};

/**
 * Перерисовывает элемент
 *
 * @param {*|jQuery|HTMLElement} node_elem
 * @param {{}} position
 */
RelationManager.prototype.repaint = function (node_elem, position) {

    var manager = this;

    var head = node_elem.find('.head');

    if (head.hasClass("jsplumb-connected")) {
        // https://codedump.io/share/xFiCLr00wPu/1/endpoint-location-not-updating-in-jsplumb-resize
        // And also, instead of using instance.repaint(ui.helper) I used instance.revalidate(ui.helper) within the resizable function. Then it perfectly worked!
        manager.renderer.revalidate(head, position);
    }

    var variant_offset = head.outerHeight();

    node_elem.find('li:visible').each(function () {
        if ($(this).hasClass("jsplumb-connected")) {
            manager.renderer.repaint($(this).attr('id'), {
                'left': position.left,
                'top': position.top + variant_offset
            });
        }

        variant_offset += $(this).outerHeight();
    });
};

/**
 * Удаление связи.
 * Сначала связь удаляется из основной хеш таблицы.
 *
 * @param {Variant} variant
 */
RelationManager.prototype.remove = function (variant) {
    Yiij.trace('Удаление визуальной связи варианта ' + variant.id);

    if (this.relations[variant.id]) {
        this.relations[variant.id].destroy();

        delete this.relations[variant.id];
    }
};

/**
 * Хеш-таблица со всеми реляциями
 * @type {{}}
 */
RelationManager.prototype.relations = {};


var Relation = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Relation.prototype = Object.create(YiijBaseModel.prototype);
Relation.prototype.constructor = Relation;

/**
 * @type {string} пара id варианта - id узла назначения
 */
Relation.prototype.id;

/**
 * @type {Variant} Вариант
 */
Relation.prototype.variant;

/**
 * @type {*|jQuery|HTMLElement}
 */
Relation.prototype.source_elem;

/**
 * @type {*|jQuery|HTMLElement}
 */
Relation.prototype.target_elem;

/**
 * @type {boolean}
 */
Relation.prototype.hidden = false;

/**
 * @type {{}}
 */
Relation.prototype.connection;

/**
 * Настройки отрисовки
 * @type {{}}
 */
Relation.prototype.settings = {
    strokeColor: '#000',
    strokeWidth: 2,
    opacity: 1,
    fill: 'none'
};

/**
 * Инициализация связи
 */
Relation.prototype.init = function () {
    if (!this.variant.target_id) {
        throw new Error('Нельзя создать связь для варианта без узла назначения.');
    }

    this.source_elem = $('#' + this.variant.id);
    this.target_elem = $('#' + this.variant.target_id).find('.head');
};

/**
 *
 * @returns {boolean}
 */
Relation.prototype.render = function () {
    if (this.hidden) {
        this.destroy();
    } else if (!this.connection && this.source_elem.length && this.target_elem.length && this.source_elem.is(':visible') && this.target_elem.is(':visible')) {
        var model_class = 'Variant';

        if (this.variant instanceof Variant) {
            model_class = 'Variant'
        } else if (this.variant instanceof GroupVariant) {
            model_class = 'GroupVariant'
        } else {
            throw new Error('Неизвестный класс варианта');
        }

        var variant_id = this.variant.id;
        var target_id = this.variant.target_id;

        this.connection = Yiij.app.getModule('editor').relationsManager.renderer.connect({
            source: this.source_elem,
            target: this.target_elem,
            endpoints: ["Blank", "Blank"],
            hoverPaintStyle: {lineWidth: 4, strokeStyle: "#434343"},
            overlays: [
                ["Label", {
                    cssClass: "variant-clear-target",
                    location: 25,
                    label: "<i class='glyphicon glyphicon-remove' title='Очистить ссылку на узел'></i>",
                    events: {
                        "tap": function () {
                            Yiij.app.getModule('editor').create({
                                'model_class': model_class,
                                'model_id': variant_id,
                                'r': {
                                    'target_id': target_id
                                },
                                'p': {
                                    'target_id': null
                                }
                            });
                        }
                    }
                }]
            ]
        });
    }
};

/**
 * @returns {boolean}
 */
Relation.prototype.destroy = function () {
    if (this.connection) {
        Yiij.app.getModule('editor').relationsManager.renderer.detach(this.connection);
        this.connection = null;
    }
};