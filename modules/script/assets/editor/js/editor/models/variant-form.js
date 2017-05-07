/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var VariantForm = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
VariantForm.prototype = Object.create(YiijBaseModel.prototype);
VariantForm.prototype.constructor = VariantForm;

/**
 * @inheritdoc
 */
VariantForm.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * @type {string} ID DOM элемента формы
 */
VariantForm.prototype.id;

/**
 * @type {Variant}
 */
VariantForm.prototype.variant;

VariantForm.prototype.isNew = 0;

VariantForm.prototype.attributes = [
    'content', 'target_id'
];


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantForm.prototype.init = function () {
    var form = this;

    /**
     *
     */
    $('body').on('submit', '#' + this.id, function () {
        form.submit();
        return false;
    });

    /**
     *
     */
    $('body').on('click', '#' + this.id + '_submit', function () {
        form.submit();
        return false;
    });

    new NodeSelect('variant-target_id');
};
/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
VariantForm.prototype.getElem = function () {
    return $('#' + this.id);
};

/**
 * Загрузка формы из данных узла
 * @param {Variant} variant
 */
VariantForm.prototype.load = function (variant, isNew) {

    var elem = this.getElem();

    this.variant = variant;

    this.isNew = isNew ? isNew : 0;

    for (var i = 0; i < this.attributes.length; i++) {
        var attr = this.attributes[i];
        if (attr == 'target_id') {
            NodeSelects['variant-target_id'].selectize.setValue(this.variant[attr]);
        } else {
            elem.find('#variant-' + attr).val(this.variant[attr]);
        }
    }
};

/**
 *
 */
VariantForm.prototype.submit = function () {

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
            value = NodeSelects['variant-target_id'].selectize.getValue();
        } else {
            value = elem.find('#variant-' + attr).val();
        }

        if (attr == 'content') {
            if(!value){
                alert('Заполните содержание');
                return;
            }

            if(value.length >= 64) {
                alert('В содержании допускается не более 64 символов!');
                return;
            }
        }

        if (this.isNew || value != this.variant[attr]) {
            command.p[attr] = value;
            command.r[attr] = this.variant[attr] ?  this.variant[attr] : '';
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
    } else if (changes > 0) {
        Yiij.app.getModule('editor').create(command);
    }

    this.hide();
};

/**
 *
 */
VariantForm.prototype.show = function () {
    this.getElem().closest('.modal').modal('show');
};

/**
 *
 */
VariantForm.prototype.hide = function () {
    this.getElem().closest('.modal').modal('hide');
};