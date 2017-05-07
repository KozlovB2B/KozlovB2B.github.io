/**
 * Представление
 *
 * @param data
 * @constructor
 */
var NodeView = function (data) {

    var n = this;

    n.template = null;

    n.css_class = 'node';

    n.content_css_class = 'node-content';

    n.number_css_class = 'node-number';

    n.groups_css_class = 'node-groups';

    n.stage_css_class = 'node-call-stage';

    /**
     * @type {string} CSS класс списка вариантов
     */
    n.variants_list_class = 'variants-list';

    $.extend(n, data);

    n.init();
};

/**
 * Инициализация объекта представления
 */
NodeView.prototype.init = function () {
    var v = this;


    Yiij.trace('Инициализация представления узлов.');

};

/**
 * Получить DOM элемент узла по ID
 *
 * @returns {*|jQuery|HTMLElement}
 */
NodeView.prototype.get = function (id) {
    var v = this;

    var node = $("#" + id);

    return node.length ? node : false;
};


/**
 * Создания DOM элемента на основе данных узла
 *
 * @param {Node} node
 * @returns {*|jQuery|HTMLElement}
 */
NodeView.prototype.create = function (node) {
    var v = this;

    if (this.template === null) {
        this.loadTemplate();
    }

    Yiij.trace('Добавляем DOM элемент для узла ' + node.id);


    var elem = $(this.template.fmt({
        'number': node.number,
        'content': node.contentStripped()
    }));

    elem.attr('id', node.id);

    elem.data('id', node.id);

    Yiij.app.getModule('editor').culmann.coordinator.append(elem);

    elem.draggable({
        handle: '.head',
        stop: function () {
            Yiij.app.getModule('editor').culmann.hideRulers();
        },
        drag: function (e, ui) {
            Yiij.app.getModule('editor').culmann.zoomCorrection(ui.position);
            Yiij.app.getModule('editor').culmann.snapToGrid(ui.position);
            Yiij.app.getModule('editor').culmann.showRulers(ui.position);
            Yiij.app.getModule('editor').relationsManager.repaint($(this), ui.position);
        }
    });

    Yiij.app.getModule('editor').relationsManager.renderer.makeTarget(elem, {
        isTarget: true,
        endpoint: "Rectangle"
    });


    elem.on('mousedown touchstart', function (e) {
        e.stopImmediatePropagation();
    });

    return elem;
};


/**
 * Создания DOM элемента на основе данных узла
 *
 * @returns {*|jQuery|HTMLElement}
 */
NodeView.prototype.loadTemplate = function () {
    var nv = this;

    $.ajax({
        'type': 'get',
        'async': false,
        'url': '/editor/views/node/template.html'
    }).done(function (data) {
        nv.template = data;
    });
};


/**
 * Рисуем варианты
 *
 * @param {Node} n
 */
NodeView.prototype.renderVariants = function (n) {
    var variants_elem = this.get(n.id).find('.' + this.variants_list_class);

    variants_elem.empty();

    var variants = Yiij.app.getModule('editor').variantController.nodeList(n.id);

    for (var i = 0; i < variants.length; i++) {
        Yiij.app.getModule('editor').variantController.renderVariant(variants[i]);
    }

    Yiij.app.getModule('editor').relationsManager.repaint(this.get(n.id), {
        top : parseInt(this.get(n.id).css('top')),
        left : parseInt(this.get(n.id).css('left'))
    });
};


/**
 * Применяем изменения, которые произошли в модели узла к DOM элементу
 *
 * @param {Node} node
 */
NodeView.prototype.applyChanges = function (node, withoutVariants) {
    Yiij.trace('Вносим изменения в DOM элемент узла ' + node.id + ', если такие имеются...');

    var changes = 0;

    var elem = this.get(node.id);

    if (node.deleted_at) {
        Yiij.trace('Скрываю DOM элемент узла ' + node.id + ' поскольку он удален.');

        elem.hide();

        return true;
    } else {
        elem.show();
    }


    var head = elem.find('.node-head');

    if (+node.is_goal) {
        head.addClass('is-goal');
    } else {
        head.removeClass('is-goal');
    }

    if (+node.normal_ending) {
        head.addClass('normal-ending');
    } else {
        head.removeClass('normal-ending');
    }

    if (node.id == Yiij.app.getModule('editor').scriptController.script.start_node_uuid) {
        head.addClass('start');
    } else {
        head.removeClass('start');
    }


    if (!elem.hasClass(this.css_class)) {
        Yiij.trace('DOM элемент узла ' + node.id + ' не имеет основгого CSS класса ' + this.css_class + '. Добавляем.');
        elem.addClass(this.css_class);
        changes++;
    }

    if (parseFloat(elem.css('top')) != node.top || parseFloat(elem.css('left')) != node.left) {
        Yiij.trace('Смена позиции DOM элемента узла ' + node.id + ' left: ' + elem.css('left') + ' -> ' + node.left + 'px, top: ' + elem.css('top') + ' ->' + node.top);
        elem.attr('style', 'left:' + node.left + 'px;top:' + node.top + 'px');
        changes++;
    }

    var groups_elem = elem.find('.' + this.groups_css_class);

    if (!groups_elem.length) {
        throw  new Error('Не найден контейнер для отображения групп ' + this.groups_css_class);
    }

    var groups_names = [];

    var groups = node.groups ? node.groups.split(',') : null;

    if (groups) {
        for (var i = 0; i < groups.length; i++) {
            var group = Yiij.app.getModule('editor').groupController.groups[groups[i]];
            if (typeof group !== 'undefined') {
                groups_names.push(group.name);
            }
        }
    }

    var groups_names_text = groups_names.join(', ');

    if (groups_elem.text() !== groups_names_text) {
        groups_elem.text(groups_names_text ? 'Группы: ' + groups_names_text : '');
    }


    var stages_elem = elem.find('.' + this.stage_css_class);

    if (!stages_elem.length) {
        throw  new Error('Не найден контейнер для отображения этапа ' + this.stage_css_class);
    }

    var stages_names_text = '';

    if (node.call_stage_id && Yiij.app.getModule('editor').stageController.stages[node.call_stage_id]) {
        stages_names_text = Yiij.app.getModule('editor').stageController.stages[node.call_stage_id];
    }

    if (stages_elem.text() !== stages_names_text) {
        stages_elem.text(stages_names_text ? 'Этап: ' + stages_names_text : '');
    }


    var number_elem = elem.find('.' + this.number_css_class);

    if (!number_elem.length) {
        throw  new Error('Не найден контейнер для номера узла с CSS классом ' + this.number_css_class);
    }

    if (number_elem.text() !== node.number) {
        number_elem.text(node.number);
    }

    var content_elem = elem.find('.' + this.content_css_class);

    if (!content_elem.length) {
        throw  new Error('Не найден контейнер для текста узла с CSS классом ' + this.content_css_class);
    }

    var content_stripped = node.contentStripped();

    if (content_elem.html() != content_stripped) {
        Yiij.trace('Текст узла ' + node.id + ' изменен');
        content_elem.html(content_stripped);
        changes++;
    }

    Yiij.trace('В DOM элемент узла ' + node.id + ' внесено изменений: ' + changes);

    if (!withoutVariants) {


        this.renderVariants(node);
    }
};