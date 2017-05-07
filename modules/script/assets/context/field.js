/**
 * Поле
 *
 * @param config
 * @constructor
 */
var Field = function (config) {

    this.code = '';
    this.name = '';
    this.type = '';
    this.type_data = '';
    this.value = '';
    this.list_data = {};

    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Field.prototype = Object.create(YiijBaseModel.prototype);
Field.prototype.constructor = Field;

/**
 *
 * @param {jQuery} el
 */
Field.prototype.renderTo = function (el) {

    var display_value = this.value;

    if (this.type == 'boolean') {
        display_value = this.value ? 'Да' : 'Нет';
    } else if (this.type == 'in') {
        display_value = this.list_data[this.value] ? this.list_data[this.value] : '-';
    } else {
        display_value = this.value ? this.value : '-';
    }

    el.html('<strong class="field-label">' + this.name + '</strong><br><strong class="field-value">' + display_value + '</strong>');
};


/**
 *
 */
Field.prototype.generateListData = function () {
    var exploded = this.type_data.split(',');

    for (var i = 0; i < exploded.length; i++) {

        var kv = exploded[i].trim().split(':');

        var key_pos = 0;

        var value_pos = 0;

        if (typeof kv[1] !== 'undefined') {
            value_pos = 1;
        }

        this.list_data[kv[key_pos]] = kv[value_pos];
    }
};


/**
 *
 */
Field.prototype.generateListHtml = function () {
    var options = '<option value="">-- ' + this.name + '</option>';

    for (var i in this.list_data) {
        if (this.list_data.hasOwnProperty(i)) {
            options += '<option value="' + i + '" ' + (this.value == i ? 'selected' : '') + '>' + this.list_data[i] + '</option>';
        }
    }

    return '<select class="form-control">' + options + '</select>';
};


/**
 * Установка событий
 */
Field.prototype.generatePopover = function () {
    var field = this;

    if (field.type == 'in') {
        field.generateListData()
    }

    var html = false;

    var input = null;

    if (this.type == 'boolean') {
        html = '<div class="checkbox "><label><input type="checkbox" data-code="' + this.code + '" class="field-input" ' + (!!this.value ? 'checked' : '') + ' /> ' + this.name + '</label></div>';
        input = $(html);
    } else {
        switch (this.type) {
            case 'string' :
                html = '<input type="text" />';
                break;
            case 'number' :
                html = '<input type="number" />';
                break;
            case  'in':
                html = this.generateListHtml();
                break;
            case  'date' :
                html = '<input type="date" />';
                break;
            case 'time':
                html = '<input type="time"/>';
                break;
            default:
                break;
        }

        if (!html) {
            return null;
        }

        input = $(html);

        input.addClass('field-input form-control').attr('data-code', this.code).attr("value", this.value);
    }


    return '<div class="script___context__field_wrapper input-group input-group-sm" >' + input.get(0).outerHTML + '<div class="input-group-btn "><span class="btn btn-success field-save-button">ok</span></div></div><div class="small field-error text-danger"></div>';
};