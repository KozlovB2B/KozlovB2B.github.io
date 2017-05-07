/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var VariantsSortableList = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
VariantsSortableList.prototype = Object.create(YiijBaseModel.prototype);

VariantsSortableList.prototype.constructor = VariantsSortableList;

/**
 * @type {string} ID DOM элемента формы
 */
VariantsSortableList.prototype.node_id;


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantsSortableList.prototype.init = function () {
    var form = this;
    this.initSortable();
};
/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantsSortableList.prototype.getElem = function () {
    return $('#script___variant___sortable_list');
};

/**
 * Загрузка формы из данных узла
 * @param {string} node_id
 */
VariantsSortableList.prototype.load = function (node_id) {
    this.node_id = node_id;

    var elem = this.getElem();

    elem.empty();

    $('#script___node___form_variants_sort_index_warning').hide();

    var variants = Yiij.app.getModule('editor').variantController.nodeList(node_id);

    for (var p = 0; p < variants.length; p++) {

        var variant = variants[p];

        elem.append('<li class="list-group-item" data-id="' + variant.id + '" id="variant-sortable-' + variant.id + '" style="' + (variant.deleted_at ? 'display:none' : null) + '">' +
            '<div class="variants-sortable-list-item-left-col">' +
            '<i class="glyphicon glyphicon-remove variants-sortable-list-variant-delete-button" title="Удалить"></i>' +
            '</div>' +
            '<div class="variants-sortable-list-item-middle-col">' +
            '<div class="variants-sortable-list-variant">' +
            '<span class="variants-sortable-list-variant-content"></span>' +
            '<br/>' +
            '<span class="variants-sortable-list-variant-target"></span>' +
            '</div>' +
            '</div>' +
            '<div class="variants-sortable-list-item-right-col">' +
            '<i class="glyphicon glyphicon-pencil variants-sortable-list-variant-edit-button" title="Редактировать"></i>' +
            '</div>' +
            '</li>');

        Yiij.app.getModule('editor').variantController.view.applyChangesEmbed(variant);
    }
};

/**
 *
 */
VariantsSortableList.prototype.initSortable = function () {
    var elem = this.getElem();

    elem.sortable({
        stop: function (event, ui) {
            var keys = [];

            elem.find("li").each(function () {
                keys.push($(this).data("id"));
            });

            $('#node-variants_sort_index').val(keys.join(','));

            $('#script___node___form_variants_sort_index_warning').show();
        }
    });
};