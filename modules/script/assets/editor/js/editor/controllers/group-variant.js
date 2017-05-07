/**
 * Контроллер вариантов ответов
 *
 * @param config
 * @constructor
 */
var GroupVariantController = function (config) {


    /**
     * Объект, отвечающий за представление узлов
     *
     * @type {GroupVariantView}
     */
    this.view = null;

    /**
     * @type {GroupVariant[]}
     */
    this.variants;
    /**
     * @type {GroupVariantForm}
     */
    this.form;
    /**
     * @type {GroupVariantFormEmbed}
     */
    this.variant_form_embed;

    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
GroupVariantController.prototype = Object.create(YiijBaseController.prototype);
GroupVariantController.prototype.constructor = GroupVariantController;

/**
 * @inheritdoc
 */
GroupVariantController.prototype.requiredConfig = function () {
    return ['view', 'variants', 'form'];
};


/**
 * Инициализация контроллера узлов
 */
GroupVariantController.prototype.init = function () {
    this.loadGroupVariantsMap();
    this.setEvents();
};

/**
 * Загружает карту вариантов ответов из данных скрипта
 */
GroupVariantController.prototype.loadGroupVariantsMap = function () {
    Yiij.trace('Загружаю карту групповых вариантов...');

    var variants = {};

    for (var i in this.variants) {
        variants[this.variants[i].id] = new GroupVariant(this.variants[i]);
    }

    this.variants = variants;
};

/**
 * Отображение всех узлов
 */
GroupVariantController.prototype.renderGroupVariants = function () {
    Yiij.trace('Рисую все групповые варианты...');

    for (var i in this.variants) {
        this.renderGroupVariant(this.variants[i]);
    }
};



/**
 * Отображение узла
 *
 * @param {GroupVariant} variant
 */
GroupVariantController.prototype.renderGroupVariant = function (variant) {

    Yiij.trace('Рисую групповой вариант ответа ' + variant.id);

    if (!this.view.get(variant.id)) {
        this.view.create(variant);
    }

    this.view.applyChanges(variant);
};

/**
 * Получение объекта варианта
 *
 * @param id
 * @returns {GroupVariant}
 */
GroupVariantController.prototype.get = function (id) {
    Yiij.trace('Получаю данные по групповому варианту ' + id + '.');

    if (typeof this.variants[id] === 'undefined') {
        throw new Error('Групповой вариант с ID' + id + ' не найден!');
    }

    return this.variants[id];
};


/**
 * Получение объекта узла
 *
 * @param {string} group_id
 * @returns {[]}
 */
GroupVariantController.prototype.groupList = function (group_id) {
    Yiij.trace('Получаю список вариантов для узла ' + group_id + '.');

    var variants_raw = {};

    var group = Yiij.app.getModule('editor').groupController.get(group_id);

    for (var i in this.variants) {
        if (this.variants.hasOwnProperty(i) && this.variants[i].group_id == group_id) {
            variants_raw[i] = this.variants[i];
        }
    }

    var nodes_result = [];

    var pushed = {};

    if (group.variants_sort_index) {
        var order = group.variants_sort_index.split(',');

        for (var o = 0; o < order.length; o++) {
            var variant_id = order[o];

            pushed[variant_id] = true;

            if (!variants_raw.hasOwnProperty(variant_id)) {
                continue;
            }

            nodes_result.push(variants_raw[variant_id]);
        }
    }


    var not_ordered = [];

    for (var v in variants_raw) {

        if (!variants_raw.hasOwnProperty(v) || pushed[v]) {
            continue;
        }

        not_ordered.push(variants_raw[v]);
    }

    not_ordered.sort(function (a, b) {
        var a_created = 0;

        var b_created = 0;

        if (a.created_at) {
            a_created = a.created_at;
        }

        if (b.created_at) {
            b_created = b.created_at;
        }

        if (a_created > b_created) {
            return 1;
        }

        if (a_created < b_created) {
            return -1;
        }

        return 0;
    });

    for (var n = 0; n < not_ordered.length; n++) {
        nodes_result.push(not_ordered[n]);
    }

    return nodes_result;
};

/**
 * Найти или создать вариант
 *
 * @param id
 * @param {{}} data
 * @returns {Group}
 */
GroupVariantController.prototype.getOrCreate = function (id, data) {
    Yiij.trace('Найти или создать групповой вариант ' + id + '.');

    if (typeof this.variants[id] === 'undefined') {
        this.variants[id] = new GroupVariant(data);
        this.renderGroupVariant(this.variants[id]);
    }

    return this.variants[id];
};

/**
 * @param {GroupVariant} n
 */
GroupVariantController.prototype.setEvents = function () {
    var vc = this;

    $("body").on('click', '.group-variant-delete-button', function () {
        var variant = vc.get($(this).closest('.group-variant').data('id'));

        Yiij.app.getModule('editor').create({
            'model_class': 'GroupVariant',
            'model_id': variant.id,
            'r': {
                'deleted_at': null
            },
            'p': {
                'deleted_at': timestamp()
            }
        });
    });


    $("body").on('click', '.group-variant-edit-button', function () {
        var variant = vc.get($(this).closest('.group-variant').data('id'));
        vc.form.load(variant);
        vc.form.show();
    });

    $("body").on('click', '.group-variant-create-button', function () {

        var variant = new GroupVariant({
            'id': UUID.generate(),
            'group_id': $(this).closest('.group').data('id'),
            'content': ''
        });

        vc.form.load(variant, 1);
        vc.form.show();
    });



    $("body").on('click', '.group_variants-sortable-list-variant-edit-button', function (e) {
        var item = $(this).closest('.list-group-item');
        var variant = vc.get(item.data('id'));
        vc.variant_form_embed.load(variant, 0);
        vc.variant_form_embed.show();
    });

    $("body").on('click', '#script___group_variant___create_embed', function (e) {
        var variant = new GroupVariant({
            'id': UUID.generate(),
            'group_id': $('#group-id').val(),
            'content': ''
        });
        vc.variant_form_embed.load(variant, 1);
        vc.variant_form_embed.show();
        $('#group_variant-embed-content').focus();
    });

    $("body").on('click', '#script___group_variant___form_embed_hide', function (e) {
        vc.variant_form_embed.hide();
    });

    $("body").on('click', '.group_variants-sortable-list-variant-delete-button', function (e) {
        var item = $(this).closest('.list-group-item');

        var variant = vc.get(item.data('id'));

        Yiij.app.getModule('editor').create({
            'model_class': 'GroupVariant',
            'model_id': variant.id,
            'r': {
                'deleted_at': null
            },
            'p': {
                'deleted_at': timestamp()
            }
        });
    });
};