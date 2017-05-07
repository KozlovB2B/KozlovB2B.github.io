/**
 *
 * @param config
 * @constructor
 * @extends {YiijBaseModel}
 */
var GroupForm = function (config) {
    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupForm.prototype = Object.create(YiijBaseModel.prototype);
GroupForm.prototype.constructor = GroupForm;

/**
 * @inheritdoc
 */
GroupForm.prototype.requiredConfig = function () {
    return ['id'];
};

/**
 * @type {string} ID DOM элемента формы
 */
GroupForm.prototype.id;

/**
 * @type {Group}
 */
GroupForm.prototype.group;

GroupForm.prototype.attributes = [
    'id', 'top', 'left', 'name', 'variants_sort_index'
];


/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupForm.prototype.init = function () {
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


};
/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
GroupForm.prototype.getElem = function () {
    return $('#' + this.id);
};

/**
 * Загрузка формы из данных узла
 * @param {Group} group
 */
GroupForm.prototype.load = function (group) {

    this.group = group;

    for (var i = 0; i < this.attributes.length; i++) {
        var attr = this.attributes[i];
        $('#group-' + attr).val(this.group[attr]);
    }

    Yiij.app.getModule('editor').groupvariantController.variants_sortable_list.load(group.id);
};

/**
 *
 */
GroupForm.prototype.submit = function () {
    var command = {
        'model_class': 'Group',
        'model_id': this.group.id,
        'p': {},
        'r': {}
    };

    var changes = 0;

    for (var i = 0; i < this.attributes.length; i++) {

        var attr = this.attributes[i];

        var value = $('#group-' + attr).val();

        if (attr == 'name') {
            if(!value){
                alert('Заполните название');

                return;
            }

            if(value.length >= 64) {
                alert('В названии допускается не более 64 символов!');
                return;
            }
        }

        if (value != this.group[attr]) {
            command.p[attr] = value;
            command.r[attr] = this.group[attr];
            changes++;
        }
    }

    if (changes > 0) {
        Yiij.app.getModule('editor').create(command);
        Yiij.app.getModule('editor').groupController.refreshGroupSelects();
    }

    this.hide();
};

/**
 *
 */
GroupForm.prototype.show = function () {
    this.getElem().closest('.modal').modal('show');
};

/**
 *
 */
GroupForm.prototype.hide = function () {
    this.getElem().closest('.modal').modal('hide');
};