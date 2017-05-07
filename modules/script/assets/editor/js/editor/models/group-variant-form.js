/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var GroupVariantForm = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupVariantForm.prototype = Object.create(YiijBaseModel.prototype);
GroupVariantForm.prototype.constructor = GroupVariantForm;

/**
 * @inheritdoc
 */
GroupVariantForm.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * @type {string} ID DOM элемента формы
 */
GroupVariantForm.prototype.id;

/**
 * @type {GroupVariant}
 */
GroupVariantForm.prototype.variant;

GroupVariantForm.prototype.isNew = 0;

GroupVariantForm.prototype.attributes = [
    'content', 'target_id'
];


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantForm.prototype.init = function () {
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

    new NodeSelect('groupvariant-target_id');
};
/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupVariantForm.prototype.getElem = function () {
    return $('#' + this.id);
};

/**
 * Загрузка формы из данных узла
 * @param {GroupVariant} variant
 * @param {boolean} isNew
 */
GroupVariantForm.prototype.load = function (variant, isNew) {

    this.variant = variant;

    this.isNew = !!isNew;

    for (var i = 0; i < this.attributes.length; i++) {
        var attr = this.attributes[i];
        if (attr == 'target_id') {
            NodeSelects['groupvariant-target_id'].selectize.setValue(this.variant[attr]);
        } else {
            $('#groupvariant-' + attr).val(this.variant[attr]);
        }
    }
};

/**
 *
 */
GroupVariantForm.prototype.submit = function () {
    var script = Yiij.app.getModule('editor').scriptController.get();

    var command = {
        'model_class': 'GroupVariant',
        'model_id': this.variant.id,
        'p': {},
        'r': {}
    };

    var changes = 0;

    for (var i = 0; i < this.attributes.length; i++) {

        var value = '';

        var attr = this.attributes[i];

        if (attr == 'target_id') {
            value = NodeSelects['groupvariant-target_id'].selectize.getValue();
        } else {
            value = $('#groupvariant-' + attr).val();
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

        command.p['script_id'] = script.id;
        command.r['script_id'] = script.id;

        command.r['content'] = command.p['content'];

        command.p['group_id'] = this.variant.group_id;
        command.r['group_id'] = this.variant.group_id;

        command.p['deleted_at'] = '';
        command.r['deleted_at'] = timestamp();
        command.p['created_at'] = timestamp();
        command.r['created_at'] = '';


        var variant = new GroupVariant(command.p);

        Yiij.app.getModule('editor').groupvariantController.variants[variant.id] = variant;
        Yiij.app.getModule('editor').groupvariantController.renderGroupVariant(variant);
        Yiij.app.getModule('editor').create(command);
    } else if (changes > 0) {
        Yiij.app.getModule('editor').create(command);
    }

    this.hide();
};

/**
 *
 */
GroupVariantForm.prototype.show = function () {
    this.getElem().closest('.modal').modal('show');
};

/**
 *
 */
GroupVariantForm.prototype.hide = function () {
    this.getElem().closest('.modal').modal('hide');
};