/**
 * Модуль контекст текущей карточки script
 *
 *
 *
 * @param id
 * @param parent
 * @param config
 * @constructor
 */
var Context = function (id, parent, config) {

    this.data = {};

    YiijBaseModule.apply(this, [id, parent, config]);
};

/**
 * Extends
 * @type {YiijBaseObject}
 */
Context.prototype = Object.create(YiijBaseModule.prototype);
Context.prototype.constructor = Context;

/**
 *
 */
Context.prototype.start = function () {
    this.initialize(0, 'default', {});
    this.setEvents();
};

/**
 *
 */
Context.prototype.initialize = function (id, type, fields) {
    this.data = {
        'id': id,
        'type': type,
        'fields': fields
    };
};

/**
 *
 */
Context.prototype.clearData = function () {
    this.data = {};
};


/**
 *
 */
Context.prototype.exportData = function () {
    if (!Object.keys(this.data).length) {
        return '';
    }

    var to_export = {};

    to_export.id = this.data.id;
    to_export.type = this.data.type;
    to_export.fields = {};

    for (var i in this.data.fields) {
        if (this.data.fields.hasOwnProperty(i)) {
            to_export.fields[this.data.fields[i].code] = this.data.fields[i].value;
        }
    }

    return to_export;
};

/**
 *
 */
Context.prototype.setData = function (data) {
    if (typeof data == 'string') {
        data = JSON.parse(fields);
    }

    if (!data.id) {
        throw 'Data must have id';
    }

    if (!data.type) {
        throw 'Data must have type';
    }

    if (!data.fields) {
        throw 'Data must have fields';
    }

    this.data = {
        'id': data.id,
        'type': data.type,
        'fields': {}
    };

    for (var i in data.fields) {
        if (data.fields.hasOwnProperty(i)) {
            this.registerField(data[i]);
        }
    }

    this.renderFields();
};

/**
 *
 * @param data
 */
Context.prototype.setData = function (data) {
    this.data = data;
    this.renderFields();
};

/**
 *
 * @param data
 */
Context.prototype.registerField = function (data) {
    var field_data = data;

    if (typeof data == 'string') {
        field_data = JSON.parse(Base64.decode(data));
    }

    if (!field_data.code) {
        return '';
    }

    if (!this.data.fields[field_data.code]) {
        this.data.fields[field_data.code] = new Field(field_data);
    }

    return field_data.code;
};

/**
 * @param {jQuery} el
 */
Context.prototype.renderField = function (el) {
    if (!el.attr('data-code')) {
        return;
    }

    if (this.data.fields[el.attr('data-code')]) {
        this.data.fields[el.attr('data-code')].renderTo(el);
    }
};


/**
 * Заполняет данные поля, которые сейчас видимы
 */
Context.prototype.renderFields = function () {
    var context = this;

    var fields = $('.performer-workspace strong.field');

    fields.each(function () {
        var field_data = $(this).attr('data-field');

        if (field_data.length) {
            $(this).attr('data-field', '');
            $(this).attr('data-code', context.registerField(field_data));
        }

        context.renderField($(this));
    });
};
/**
 * Установка событий
 */
Context.prototype.setEvents = function () {
    var context = this;

    $('body').on('click', '.field-save-button', function () {

        var input = $(this).closest('.script___context__field_wrapper').find('.field-input');

        var code = input.attr('data-code');

        if (!context.data || !context.data.fields || !context.data.fields[code]) {
            return;
        }

        if (input.attr('type') == 'checkbox') {
            context.data.fields[code].value = input.prop('checked') ? 1 : 0;
        } else {
            context.data.fields[code].value = input.val();
        }

        context.renderFields();

        $('strong.field').popover('destroy');
    });

    $('body').on('click', 'strong.field[data-code]', function () {

        $('strong.field').popover('destroy');

        var code = $(this).attr('data-code');

        if (!context.data || !context.data.fields || !context.data.fields[code]) {
            return true;
        }

        console.log(context.data.fields);

        var html = context.data.fields[code].generatePopover();

        if (!html) {
            return;
        }

        $(this).popover({
            animation: false,
            html: true,
            placement: 'bottom',
            trigger: 'manual',
            content: html
        }).popover('show');
    });

    $('html').on('click', function (e) {
        if (!$(e.target).closest('strong.field').length && !$(e.target).closest('.popover').length) {
            $('strong.field').popover('destroy');
        }
    });
};
