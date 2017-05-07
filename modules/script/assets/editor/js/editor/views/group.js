/**
 * Представление
 *
 * @param {{}} config
 * @constructor
 */
var GroupView = function (config) {

    var n = this;

    n.template = null;

    n.css_class = 'group';

    n.name_css_class = 'group-name';
    /**
     * @type {string} CSS класс списка вариантов
     */
    n.variants_list_class = 'group-variants-list';

    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupView.prototype = Object.create(YiijBaseModel.prototype);
GroupView.prototype.constructor = GroupView;

/**
 * Получить DOM элемент группы по ID
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupView.prototype.get = function (id) {
    var group = $("#" + id);

    return group.length ? group : false;
};


/**
 * Создания DOM элемента на основе данных группы
 *
 * @param {Group} group
 * @returns {*|jQuery|HTMLElement}
 */
GroupView.prototype.create = function (group) {

    if (this.template === null) {
        this.loadTemplate();
    }

    Yiij.trace('Добавляем DOM элемент для группы ' + group.id);

    var elem = $(this.template.fmt({
        'name': group.name
    }));

    elem.attr('id', group.id);

    elem.data('id', group.id);

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

    elem.on('mousedown touchstart', function (e) {
        e.stopImmediatePropagation();
    });

    return elem;
};


/**
 * Создания DOM элемента на основе данных группы
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupView.prototype.loadTemplate = function () {
    var nv = this;

    $.ajax({
        'type': 'get',
        'async': false,
        'url': '/editor/views/group/template.html'
    }).done(function (data) {
        nv.template = data;
    });
};

/**
 * Рисуем варианты
 *
 * @param {Group} g
 */
GroupView.prototype.renderVariants = function (g) {
    var variants_elem = this.get(g.id).find('.' + this.variants_list_class);

    variants_elem.empty();

    var variants = Yiij.app.getModule('editor').groupvariantController.groupList(g.id);

    for (var i = 0; i < variants.length; i++) {
        Yiij.app.getModule('editor').groupvariantController.renderGroupVariant(variants[i]);
    }

    Yiij.app.getModule('editor').relationsManager.repaint(this.get(g.id), {
        top : parseInt(this.get(g.id).css('top')),
        left : parseInt(this.get(g.id).css('left'))
    });
};


/**
 * Применяем изменения, которые произошли в модели группы к DOM элементу
 *
 * @param {Group} group
 */
GroupView.prototype.applyChanges = function (group, withoutVariants) {
    Yiij.trace('Вносим изменения в DOM элемент группы ' + group.id + ', если такие имеются...');

    var changes = 0;

    var elem = this.get(group.id);

    if (group.deleted_at) {

        Yiij.trace('Скрываю DOM элемент группы ' + group.id + ' поскольку он удален.');

        elem.hide();

        return true;
    } else {
        elem.show();
    }


    if (!elem.hasClass(this.css_class)) {
        Yiij.trace('DOM элемент группы ' + group.id + ' не имеет основгого CSS класса ' + this.css_class + '. Добавляем.');
        elem.addClass(this.css_class);
        changes++;
    }

    if (parseFloat(elem.css('top')) != group.top || parseFloat(elem.css('left')) != group.left) {
        Yiij.trace('Смена позиции DOM элемента группы ' + group.id + ' left: ' + elem.css('left') + ' -> ' + group.left + 'px, top: ' + elem.css('top') + ' ->' + group.top);
        elem.attr('style', 'left:' + group.left + 'px;top:' + group.top + 'px');
        changes++;
    }

    var name_elem = elem.find('.' + this.name_css_class);

    if (!name_elem.length) {
        throw  new Error('Не найден контейнер для названия группы с CSS классом ' + this.name_css_class);
    }

    if (name_elem.html() != group.name) {
        Yiij.trace('Название группы ' + group.id + ' изменено');
        name_elem.html(group.name);
        changes++;
    }

    Yiij.trace('В DOM элемент группы ' + group.id + ' внесено изменений: ' + changes);

    if (!withoutVariants) {
        this.renderVariants(group);
    }
};