/**
 * Представление
 *
 * @param config
 * @constructor
 */
var NodeCloneView = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
NodeCloneView.prototype = Object.create(YiijBaseModel.prototype);
NodeCloneView.prototype.constructor = NodeCloneView;

/**
 * Применяет изменения клона к моделям редактора
 * @param {NodeClone} clone
 */
NodeCloneView.prototype.applyChanges = function (clone) {
    var to_data = JSON.parse(clone.to_data);

    var target = Yiij.app.getModule('editor').nodeController.getOrCreate(to_data.node.id, to_data.node);

    for (var i = 0; i < to_data.variants.length; i++) {
        Yiij.app.getModule('editor').variantController.getOrCreate(to_data.variants[i].id, to_data.variants[i]);
    }

    if (clone.deleted_at) {
        target.deleted_at = timestamp();
    } else {
        target.deleted_at = null;
    }

    Yiij.app.getModule('editor').nodeController.renderNode(target);
};