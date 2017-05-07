/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var GroupVariantFormEmbed = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupVariantFormEmbed.prototype = Object.create(YiijBaseModel.prototype);
GroupVariantFormEmbed.prototype.constructor = GroupVariantFormEmbed;

/**
 * @inheritdoc
 */
GroupVariantFormEmbed.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * @type {string} ID DOM элемента формы
 */
GroupVariantFormEmbed.prototype.id;

/**
 * @type {Variant}
 */
GroupVariantFormEmbed.prototype.group_variant;

GroupVariantFormEmbed.prototype.isNew = 0;

GroupVariantFormEmbed.prototype.attributes = [
    'content', 'target_id'
];


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantFormEmbed.prototype.getElem = function () {
    return $('#' + this.id);
};

/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantFormEmbed.prototype.init = function () {
    var form = this;

    /**
     *
     */
    $('body').on('submit', '#' + this.id, function () {
        form.submit();
        return false;
    });

    new NodeSelect('group_variant-embed-target_id');
};

/**
 * Загрузка формы из данных узла
 * @param {Variant} group_variant
 */
GroupVariantFormEmbed.prototype.load = function (group_variant, isNew) {
    var elem = this.getElem();

    this.group_variant = group_variant;

    this.isNew = isNew ? isNew : 0;

    for (var i = 0; i < this.attributes.length; i++) {
        var attr = this.attributes[i];
        if (attr == 'target_id') {
            NodeSelects['group_variant-embed-target_id'].selectize.setValue(this.group_variant[attr]);
        } else {
            elem.find('#group_variant-embed-' + attr).val(this.group_variant[attr]);
        }
    }
};

/**
 *
 */
GroupVariantFormEmbed.prototype.submit = function () {

    var command = {
        'model_class': 'GroupVariant',
        'model_id': this.group_variant.id,
        'p': {},
        'r': {}
    };

    var changes = 0;

    var elem = this.getElem();

    for (var i = 0; i < this.attributes.length; i++) {

        var value = '';

        var attr = this.attributes[i];

        if (attr == 'target_id') {
            value = NodeSelects['group_variant-embed-target_id'].selectize.getValue();
        } else {
            value = elem.find('#group_variant-embed-' + attr).val();
        }

        if (attr == 'content') {
            if (!value) {
                alert('Заполните содержание');
                return;
            }

            if (value.length >= 64) {
                alert('В содержании допускается не более 64 символов!');
                return;
            }
        }

        if (this.isNew || value != this.group_variant[attr]) {
            command.p[attr] = value;
            command.r[attr] = this.group_variant[attr] ? this.group_variant[attr] : '';
            changes++;
        }
    }

    if (this.isNew) {
        command.p['id'] = this.group_variant.id;
        command.r['id'] = this.group_variant.id;
        command.p['group_id'] = this.group_variant.group_id;
        command.r['group_id'] = this.group_variant.group_id;
        command.p['deleted_at'] = '';
        command.r['deleted_at'] = timestamp();
        command.p['created_at'] = timestamp();
        command.r['created_at'] = '';
        command.r['content'] = command.p['content'];
        //var group_variant = new GroupVariant(command.p);
        //Yiij.app.getModule('editor').groupvariantController.variants[group_variant.id] = group_variant;
        //Yiij.app.getModule('editor').groupvariantController.renderGroupVariant(group_variant);
        Yiij.app.getModule('editor').create(command);
        Yiij.app.getModule('editor').groupvariantController.variants_sortable_list.load(this.group_variant.group_id);
    } else if (changes > 0) {
        Yiij.app.getModule('editor').create(command);
    }

    this.hide();
};

/**
 *
 */
GroupVariantFormEmbed.prototype.show = function () {
    Yiij.app.getModule('editor').groupvariantController.variants_sortable_list.getElem().hide();
    this.getElem().show();
};

/**
 *
 */
GroupVariantFormEmbed.prototype.hide = function () {
    Yiij.app.getModule('editor').groupvariantController.variants_sortable_list.getElem().show();
    this.getElem().hide();
};