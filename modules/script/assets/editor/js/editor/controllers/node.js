/**
 * Контроллер узла
 *
 * @param config
 * @constructor
 */
var NodeController = function (config) {

    /**
     * Объект, отвечающий за представление узлов
     *
     * @type {NodeView}
     */
    this.view;

    /**
     * @type {Node[]}
     */
    this.nodes;

    /**
     * @type {Node[]}
     */
    this.nodes_list = [];

    /**
     * @type {NodeForm}
     */
    this.node_form;

    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
NodeController.prototype = Object.create(YiijBaseController.prototype);
NodeController.prototype.constructor = NodeController;

/**
 * @inheritdoc
 */
NodeController.prototype.requiredConfig = function () {
    return ['view', 'nodes'];
};


/**
 * Инициализация контроллера узлов
 */
NodeController.prototype.init = function () {
    Yiij.trace('Инициализация контроллера узлов.');

    this.loadNodesMap();
    this.setEvents();
};


/**
 * Загружает карту узлов из данных скрипта
 */
NodeController.prototype.loadNodesMap = function () {
    Yiij.trace('Загружаю карту узлов...');

    var nodes = {};

    for (var i in this.nodes) {
        if (typeof nodes[this.nodes[i].id] !== 'undefined') {
            throw  new Error('Ошибка в данных скрипта. Существует как минимум 2 узла с id ' + this.nodes[i].id)
        }

        nodes[this.nodes[i].id] = new Node(this.nodes[i]);

    }

    this.nodes = nodes;

    this.reloadNodesList();
};

/**
 *
 */
NodeController.prototype.reloadNodesList = function () {
    Yiij.trace('Обновляю список узлов...');

    this.nodes_list = [];

    for (var i in this.nodes) {
        if (!this.nodes[i].deleted_at) {
            this.nodes_list.push({
                'id': this.nodes[i].id,
                'number': this.nodes[i].number,
                'content': this.nodes[i].contentStripped()
            });
        }
    }
};


/**
 *
 */
NodeController.prototype.refreshNodeSelects = function () {
    this.reloadNodesList();

    for (var select in NodeSelects) {

        var value = NodeSelects[select].selectize.getValue();

        for (var i in NodeSelects[select].selectize.options) {

            var option = NodeSelects[select].selectize.options[i];

            var option_exist = false;

            for (var j = 0; j < this.nodes_list.length; j++) {
                if (this.nodes_list[j].id == option.id) {
                    option_exist = true;
                    break;
                }
            }

            if (!option_exist && value != option.id) {
                NodeSelects[select].selectize.removeOption(option.id);
            }

        }

        for (var j = 0; j < this.nodes_list.length; j++) {
            NodeSelects[select].selectize.addOption(this.nodes_list[j]);
            NodeSelects[select].selectize.updateOption(this.nodes_list[j].id, this.nodes_list[j]);
        }

        NodeSelects[select].selectize.refreshOptions(false);
    }
};

/**
 * Найти или создать узел
 *
 * @param id
 * @param {{}} data
 * @returns {Node}
 */
NodeController.prototype.getOrCreate = function (id, data) {
    Yiij.trace('Найти или создать узел ' + id + '.');

    if (typeof this.nodes[id] === 'undefined') {
        this.nodes[id] = new Node(data);
        this.renderNode(this.nodes[id]);
    }

    return this.nodes[id];
};

/**
 * Получение объекта узла
 *
 * @param id
 * @returns {Node}
 */
NodeController.prototype.get = function (id) {
    Yiij.trace('Получаю данные по узлу ' + id + '.');

    if (typeof this.nodes[id] === 'undefined') {
        throw new Error('Узел с ID' + id + ' не найден!');
    }

    return this.nodes[id];
};

/**
 * Поиск узла по номеру
 *
 * @param {integer} number
 * @returns {*}
 */
NodeController.prototype.findByNumber = function (number) {
    Yiij.trace('Поиск узла по номеру ' + number + '.');

    for (var i in this.nodes) {
        if (this.nodes.hasOwnProperty(i) && this.nodes[i].number == number) {
            return this.nodes[i]
        }
    }

    return null;
};

/**
 * Отображение всех узлов
 */
NodeController.prototype.renderVariants = function () {
    Yiij.trace('Рисую все узлы...');

    for (var i in this.nodes) {
        this.view.renderVariants(this.nodes[i]);
    }
};

/**
 * Отображение всех узлов
 */
NodeController.prototype.renderNodes = function (withoutVariants) {
    Yiij.trace('Рисую все узлы...');

    for (var i in this.nodes) {
        this.renderNode(this.nodes[i], withoutVariants);
    }
};


/**
 * Перерисовка связей для узла
 * @param id
 */
NodeController.prototype.repaintNodeRelations = function (id) {
    Yiij.trace('Обновляю представления связей для узла ' + id);

    var node = this.get(id);
    var elem = this.view.get(id);

    Yiij.app.getModule('editor').relationsManager.repaint(elem, {
        top: parseInt(node.top),
        left: parseInt(node.left)
    });
};


/**
 * Отображение узла
 *
 * @param {Node} n
 */
NodeController.prototype.renderNode = function (n, withoutVariants) {
    Yiij.trace('Рисую узел ' + n.id);

    if (!this.view.get(n.id)) {
        this.view.create(n);
    }

    this.view.applyChanges(n, withoutVariants);
};

/**
 * Отображение узла
 *
 * @param {Node} n
 */
NodeController.prototype.setEvents = function (n) {
    var nc = this;

    $("body").on('dragstop', '.' + this.view.css_class, function (e) {
        var node = nc.get($(this).data('id'));
        var current_top = parseInt($(this).css('top'));
        var current_left = parseInt($(this).css('left'));

        if (node.top != current_top || node.left != current_left) {

            Yiij.app.getModule('editor').create({
                'model_class': 'Node',
                'model_id': node.id,
                'r': {
                    'top': node.top,
                    'left': node.left
                },
                'p': {
                    'top': current_top,
                    'left': current_left
                }
            });
        }
    });

    $("body").on('click', '.node-call-button', function () {
        var node = nc.get($(this).closest('.node').data('id'));
        window.location.hash = '#/call/' + node.script_id + '/mode/test/start/' + node.id;
    });

    //$("body").on('click', '.node-duplicate-button', function () {
    //    var node = nc.get($(this).closest('.node').data('id'));
    //    Yiij.app.getModule('editor').culmann.focus(node.left, node.top);
    //});

    $("body").on('click', '.node-edit-button', function (e) {
        var node = nc.get($(this).closest('.node').data('id'));
        nc.node_form.load(node);
        nc.node_form.show();
    });

    $("body").on('click', '.node-edit-button-from-previous', function (e) {
        if (nc.node_form.hasChanged && !confirm('Текущие данные узла были изменены. Вы действительно хотите начать редактирование другого узла?')) {
            return false;
        }

        var node = nc.get($(this).data('id'));
        nc.node_form.load(node);
        nc.node_form.show();
    });

    $("body").on('click', '.node-delete-button', function (e) {
        var node = nc.get($(this).closest('.node').data('id'));

        Yiij.app.getModule('editor').create({
            'model_class': 'Node',
            'model_id': node.id,
            'r': {
                'deleted_at': null
            },
            'p': {
                'deleted_at': Math.round((new Date()).getTime() / 1000)
            }
        });


    });
};

