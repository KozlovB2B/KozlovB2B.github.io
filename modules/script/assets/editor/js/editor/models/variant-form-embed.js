/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var VariantFormEmbed = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
VariantFormEmbed.prototype = Object.create(YiijBaseModel.prototype);
VariantFormEmbed.prototype.constructor = VariantFormEmbed;

/**
 * @inheritdoc
 */
VariantFormEmbed.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * @type {string} ID DOM элемента формы
 */
VariantFormEmbed.prototype.id;

/**
 * @type {Variant}
 */
VariantFormEmbed.prototype.variant;

VariantFormEmbed.prototype.isNew = 0;

VariantFormEmbed.prototype.attributes = [
    'content', 'target_id'
];


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantFormEmbed.prototype.getElem = function () {
    return $('#' + this.id);
};

/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantFormEmbed.prototype.init = function () {
    var form = this;

    /**
     *
     */
    $('body').on('submit', '#' + this.id, function () {
        form.submit();
        return false;
    });

    new NodeSelect('variant-embed-target_id');
};

/**
 * Загрузка формы из данных узла
 * @param {Variant} variant
 */
VariantFormEmbed.prototype.load = function (variant, isNew) {
    var elem = this.getElem();

    this.variant = variant;

    this.isNew = isNew ? isNew : 0;

    for (var i = 0; i < this.attributes.length; i++) {
        var attr = this.attributes[i];
        if (attr == 'target_id') {
            NodeSelects['variant-embed-target_id'].selectize.setValue(this.variant[attr]);
        } else {
            elem.find('#variant-embed-' + attr).val(this.variant[attr]);
        }
    }
};

/**
 *
 */
VariantFormEmbed.prototype.submit = function () {

    var command = {
        'model_class': 'Variant',
        'model_id': this.variant.id,
        'p': {},
        'r': {}
    };

    var changes = 0;

    var elem = this.getElem();

    for (var i = 0; i < this.attributes.length; i++) {

        var value = '';

        var attr = this.attributes[i];

        if (attr == 'target_id') {
            value = NodeSelects['variant-embed-target_id'].selectize.getValue();
        } else {
            value = elem.find('#variant-embed-' + attr).val();
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

        if (this.isNew || value != this.variant[attr]) {
            command.p[attr] = value;
            command.r[attr] = this.variant[attr] ? this.variant[attr] : '';
            changes++;
        }
    }

    if (this.isNew) {
        command.p['id'] = this.variant.id;
        command.r['id'] = this.variant.id;
        command.p['node_id'] = this.variant.node_id;
        command.r['node_id'] = this.variant.node_id;
        command.p['deleted_at'] = '';
        command.r['deleted_at'] = timestamp();
        command.p['created_at'] = timestamp();
        command.r['created_at'] = '';
        command.r['content'] = command.p['content'];

        var variant = new Variant(command.p);
        Yiij.app.getModule('editor').variantController.variants[variant.id] = variant;
        Yiij.app.getModule('editor').variantController.renderVariant(variant);
        Yiij.app.getModule('editor').create(command);
        Yiij.app.getModule('editor').variantController.variants_sortable_list.load(this.variant.node_id);
    } else if (changes > 0) {
        Yiij.app.getModule('editor').create(command);
    }

    this.hide();
};

/**
 *
 */
VariantFormEmbed.prototype.show = function () {
    Yiij.app.getModule('editor').variantController.variants_sortable_list.getElem().hide();
    this.getElem().show();
};

/**
 *
 */
VariantFormEmbed.prototype.hide = function () {
    Yiij.app.getModule('editor').variantController.variants_sortable_list.getElem().show();
    this.getElem().hide();
};