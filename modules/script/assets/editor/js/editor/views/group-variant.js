/**
 * Представление варианта
 *
 * @param config
 * @constructor
 */
var GroupVariantView = function (config) {

    this.css_class = 'group-variant';

    this.content_css_class = 'group-variant-content';


    /**
     * @type {string} CSS класс списка вариантов
     */
    this.variants_list_class = 'group-variants-list';

    this.template = null;

    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupVariantView.prototype = Object.create(YiijBaseModel.prototype);
GroupVariantView.prototype.constructor = GroupVariantView;


/**
 * Получить DOM элемент варианта по ID
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantView.prototype.get = function (id) {
    var variant = $("#" + id);

    return variant.length ? variant : false;
};

/**
 * Создания DOM элемента на основе данных варианта
 *
 * @param {GroupVariant} variant
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantView.prototype.create = function (variant) {

    Yiij.trace('Добавляем DOM элемент для варианта ' + variant.id);

    if (this.template === null) {
        this.loadTemplate();
    }

    var elem = $(this.template.fmt({
        'id': variant.id,
        'content': variant.content
    }));

    var group = Yiij.app.getModule('editor').groupController.get(variant.group_id);

    var group_elem = Yiij.app.getModule('editor').groupController.view.get(group.id);

    if (!group_elem) {
        throw new Error('Не найден DOM элемент для узла '.group.id);
    }

    elem.attr('id', variant.id);
    elem.data('id', variant.id);

    group_elem.find('.' + this.variants_list_class).append(elem);

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
GroupVariantView.prototype.loadTemplate = function () {
    var vv = this;

    $.ajax({
        'type': 'get',
        'async': false,
        'url': '/editor/views/group-variant/template.html'
    }).done(function (data) {
        vv.template = data;
    });
};

/**
 * Применяем изменения, которые произошли в модели варианта к DOM элементу
 *
 * @param {Variant} variant
 */
GroupVariantView.prototype.applyChangesEmbed = function (variant) {
    var v = this;

    var elem = $("#group_variant-sortable-" + variant.id);

    if (!elem.length) {
        return;
    }

    Yiij.trace('Вносим изменения в DOM элемент группового варианта ' + variant.id + ' из списка в окне редактирования узла.');

    var content_elem = elem.find('.group_variants-sortable-list-variant-content');

    var target_elem = elem.find('.group_variants-sortable-list-variant-target');

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

/**
 * Применяем изменения, которые произошли в модели варианта к DOM элементу
 *
 * @param {GroupVariant} variant
 */
GroupVariantView.prototype.applyChanges = function (variant) {
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
        Yiij.trace('Скрываю DOM элемент групп. варианта ' + variant.id + ' поскольку он удален.');

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