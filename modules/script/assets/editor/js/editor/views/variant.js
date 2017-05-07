/**
 * Представление варианта
 *
 * @param data
 * @constructor
 */
var VariantView = function (data) {

    this.css_class = 'variant';

    this.content_css_class = 'variant-content';

    /**
     * @type {string} CSS класс списка вариантов
     */
    this.variants_list_class = 'variants-list';

    this.template = null;

    $.extend(this, data);

    this.init();
};

/**
 * Инициализация объекта представления
 */
VariantView.prototype.init = function () {
    Yiij.trace('Инициализация представления вариантов.');
};

/**
 * Получить DOM элемент варианта по ID
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantView.prototype.get = function (id) {
    var variant = $("#" + id);

    return variant.length ? variant : false;
};

/**
 * Создания DOM элемента на основе данных варианта
 *
 * @param {Variant} variant
 * @returns {*|jQuery|HTMLElement}
 */
VariantView.prototype.create = function (variant) {

    Yiij.trace('Добавляем DOM элемент для варианта ' + variant.id);

    if (this.template === null) {
        this.loadTemplate();
    }

    var elem = $(this.template.fmt({
        'id': variant.id,
        'content': variant.content
    }));

    var node = Yiij.app.getModule('editor').nodeController.get(variant.node_id);

    var node_elem = Yiij.app.getModule('editor').nodeController.view.get(node.id);

    if (!node_elem) {
        throw new Error('Не найден DOM элемент для узла '.node.id);
    }

    elem.attr('id', variant.id);
    elem.data('id', variant.id);

    node_elem.find('.' + this.variants_list_class).append(elem);

    Yiij.app.getModule('editor').relationsManager.renderer.makeSource(elem, {
        anchor: ["Left", "Right"],
        endpoint: ["Rectangle", {width: 1, height: 1}],
        maxConnections: 1,
        dragOptions: {
            start: function () {
                var matrix = Yiij.app.getModule('editor').culmann.getMatrix();
                Yiij.app.getModule('editor').relationsManager.renderer.setZoom(matrix.x_scale);
            }
        }
    });

    return elem;
};

/**
 * Создания DOM элемента на основе данных узла
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantView.prototype.loadTemplate = function () {
    var vv = this;

    $.ajax({
        'type': 'get',
        'async': false,
        'url': '/editor/views/variant/template.html'
    }).done(function (data) {
        vv.template = data;
    });
};

/**
 * Применяем изменения, которые произошли в модели варианта к DOM элементу
 *
 * @param {Variant} variant
 */
VariantView.prototype.applyChanges = function (variant) {
    var v = this;

    this.applyChangesEmbed(variant);

    Yiij.trace('Вносим изменения в DOM элемент варианта ' + variant.id + ', если такие имеются...');

    var changes = 0;

    var elem = v.get(variant.id);

    /**
     *
     * @type {RelationManager|*}
     */
    var relations_manager = Yiij.app.getModule('editor').relationsManager;

    if (variant.deleted_at) {
        Yiij.trace('Скрываю DOM элемент варианта ' + variant.id + ' поскольку он удален.');

        elem.hide();

        relations_manager.remove(variant);

        return true;
    } else {
        elem.show();
    }

    relations_manager.remove(variant);

    if (variant.target_id) {
        relations_manager.create(variant);
        changes++;
    }

    if (!elem.hasClass(v.css_class)) {
        Yiij.trace('DOM элемент варианта ' + variant.id + ' не имеет основгого CSS класса ' + v.css_class + '. Добавляем.');
        elem.addClass(v.css_class);
        changes++;
    }

    var content_elem = elem.find('.' + v.content_css_class);

    if (!content_elem.length) {
        throw  new Error('Не найден контейнер для текста варианта с CSS классом ' + v.content_css_class);
    }

    if (content_elem.html() != variant.content) {
        Yiij.trace('Текст варианта ' + variant.id + ' изменен');
        content_elem.html(variant.content);
        changes++;
    }

    Yiij.trace('В DOM элемент варианта ' + variant.id + ' внесено изменений: ' + changes);


};

/**
 * Применяем изменения, которые произошли в модели варианта к DOM элементу
 *
 * @param {Variant} variant
 */
VariantView.prototype.applyChangesEmbed = function (variant) {
    var v = this;

    var elem = $("#variant-sortable-" + variant.id);

    if (!elem.length) {
        return;
    }

    Yiij.trace('Вносим изменения в DOM элемент варианта ' + variant.id + ' из списка в окне редактирования узла.');

    var content_elem = elem.find('.variants-sortable-list-variant-content');

    var target_elem = elem.find('.variants-sortable-list-variant-target');

    if (variant.deleted_at) {
        elem.hide();
        return true;
    } else {
        elem.show();
    }

    if (variant.target_id) {
        var node = Yiij.app.getModule('editor').nodeController.get(variant.target_id);
        target_elem.text('#' + node.number + ' ' + node.contentStripped());
    } else {
        target_elem.text('--');
    }

    if (content_elem.text() != variant.content) {
        content_elem.text(variant.content);
    }
};