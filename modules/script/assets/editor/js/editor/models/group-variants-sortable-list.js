/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var GroupVariantsSortableList = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupVariantsSortableList.prototype = Object.create(YiijBaseModel.prototype);

GroupVariantsSortableList.prototype.constructor = GroupVariantsSortableList;

/**
 * @type {string} ID DOM элемента формы
 */
GroupVariantsSortableList.prototype.group_id;


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantsSortableList.prototype.init = function () {
    this.initSortable();
};
/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantsSortableList.prototype.getElem = function () {
    return $('#script___group_variant___sortable_list');
};

/**
 * Загрузка формы из данных узла
 * @param {string} group_id
 */
GroupVariantsSortableList.prototype.load = function (group_id) {
    this.group_id = group_id;

    var elem = this.getElem();

    elem.empty();

    $('#script___group___form_group_variants_sort_index_warning').hide();

    var group_variants = Yiij.app.getModule('editor').groupvariantController.groupList(group_id);

    for (var p = 0; p < group_variants.length; p++) {

        var variant = group_variants[p];

        elem.append('<li class="list-group-item" data-id="' + variant.id + '" id="group_variant-sortable-' + variant.id + '" style="' + (variant.deleted_at ? 'display:none' : null) + '">' +
            '<div class="group_variants-sortable-list-item-left-col">' +
            '<i class="glyphicon glyphicon-remove group_variants-sortable-list-variant-delete-button" title="Удалить"></i>' +
            '</div>' +
            '<div class="group_variants-sortable-list-item-middle-col">' +
            '<div class="group_variants-sortable-list-variant">' +
            '<span class="group_variants-sortable-list-variant-content"></span>' +
            '<br/>' +
            '<span class="group_variants-sortable-list-variant-target"></span>' +
            '</div>' +
            '</div>' +
            '<div class="group_variants-sortable-list-item-right-col">' +
            '<i class="glyphicon glyphicon-pencil group_variants-sortable-list-variant-edit-button" title="Редактировать"></i>' +
            '</div>' +
            '</li>');

        Yiij.app.getModule('editor').groupvariantController.view.applyChangesEmbed(variant);
    }
};

/**
 *
 */
GroupVariantsSortableList.prototype.initSortable = function () {
    var elem = this.getElem();

    elem.sortable({
        stop: function (event, ui) {
            var keys = [];

            elem.find("li").each(function () {
                keys.push($(this).data("id"));
            });

            $('#group-variants_sort_index').val(keys.join(','));

            $('#script___group___form_group_variants_sort_index_warning').show();
        }
    });
};