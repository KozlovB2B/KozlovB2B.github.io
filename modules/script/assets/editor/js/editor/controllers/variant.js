/**
 * Контроллер вариантов ответов
 *
 * @param config
 * @constructor
 */
var VariantController = function (config) {


    /**
     * Объект, отвечающий за представление узлов
     *
     * @type {VariantView}
     */
    this.view = null;

    /**
     * @type {Variant[]}
     */
    this.variants;
    /**
     * @type {VariantForm}
     */
    this.variant_form;
    /**
     * @type {VariantFormEmbed}
     */
    this.variant_form_embed;
    /**
     * @type {VariantsSortableList}
     */
    this.variants_sortable_list;

    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
VariantController.prototype = Object.create(YiijBaseController.prototype);
VariantController.prototype.constructor = VariantController;

/**
 * @inheritdoc
 */
VariantController.prototype.requiredConfig = function () {
    return ['view', 'variants'];
};


/**
 * Инициализация контроллера узлов
 */
VariantController.prototype.init = function () {
    Yiij.trace('Инициализация контроллера вариантов ответов.');

    this.loadVariantsMap();
    this.setEvents();
};

/**
 * Загружает карту вариантов ответов из данных скрипта
 */
VariantController.prototype.loadVariantsMap = function () {
    Yiij.trace('Загружаю карту вариантов...');

    var variants = {};

    for (var i in this.variants) {

        if (typeof variants[this.variants[i].id] !== 'undefined') {
            throw  new Error('Нарушена целостность данных: есть 2 варианта с id ' + this.variants[i].id)
        }

        variants[this.variants[i].id] = new Variant(this.variants[i]);
    }

    this.variants = variants;
};

/**
 * Отображение всех узлов
 */
VariantController.prototype.renderVariants = function () {
    Yiij.trace('Рисую все варианты...');

    for (var i in this.variants) {
        this.renderVariant(this.variants[i]);
    }
};


/**
 * Отображение узла
 *
 * @param {Variant} variant
 */
VariantController.prototype.renderVariant = function (variant) {

    Yiij.trace('Рисую вариант ответа ' + variant.id);

    if (!this.view.get(variant.id)) {
        this.view.create(variant);
    }

    this.view.applyChanges(variant);
};

/**
 * Получение объекта узла
 *
 * @param id
 * @returns {Variant}
 */
VariantController.prototype.get = function (id) {
    Yiij.trace('Получаю данные по варианту ' + id + '.');

    if (typeof this.variants[id] === 'undefined') {
        throw new Error('Вариант с ID' + id + ' не найден!');
    }

    return this.variants[id];
};

/**
 * Получение объекта узла
 *
 * @param {string} node_id
 * @returns {[]}
 */
VariantController.prototype.nodeList = function (node_id) {
    Yiij.trace('Получаю список вариантов для узла ' + node_id + '.');

    var variants_raw = {};

    var node = Yiij.app.getModule('editor').nodeController.get(node_id);

    for (var i in this.variants) {
        if (this.variants.hasOwnProperty(i) && this.variants[i].node_id == node_id) {
            variants_raw[i] = this.variants[i];
        }
    }

    var nodes_result = [];

    var pushed = {};

    if (node.variants_sort_index) {
        var order = node.variants_sort_index.split(',');

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
 * @returns {Node}
 */
VariantController.prototype.getOrCreate = function (id, data) {
    Yiij.trace('Найти или создать вариант ' + id + '.');

    if (typeof this.variants[id] === 'undefined') {
        this.variants[id] = new Variant(data);
        this.renderVariant(this.variants[id]);
    }

    return this.variants[id];
};

/**
 * Отображение узла
 *
 * @param {Variant} n
 */
VariantController.prototype.setEvents = function (n) {
    var vc = this;

    $("body").on('click', '.variant-delete-button', function (e) {
        var variant = vc.get($(this).closest('.variant').data('id'));

        Yiij.app.getModule('editor').create({
            'model_class': 'Variant',
            'model_id': variant.id,
            'r': {
                'deleted_at': null
            },
            'p': {
                'deleted_at': timestamp()
            }
        });
    });

    $("body").on('click', '.variants-sortable-list-variant-delete-button', function (e) {
        var item = $(this).closest('.list-group-item');

        var variant = vc.get(item.data('id'));

        Yiij.app.getModule('editor').create({
            'model_class': 'Variant',
            'model_id': variant.id,
            'r': {
                'deleted_at': null
            },
            'p': {
                'deleted_at': timestamp()
            }
        });
    });


    $("body").on('click', '.variant-edit-button', function (e) {
        var variant = vc.get($(this).closest('.variant').data('id'));
        vc.variant_form.load(variant);
        vc.variant_form.show();
    });


    $("body").on('click', '.variants-sortable-list-variant-edit-button', function (e) {
        var item = $(this).closest('.list-group-item');
        var variant = vc.get(item.data('id'));
        vc.variant_form_embed.load(variant, 0);
        vc.variant_form_embed.show();
    });

    $("body").on('click', '#script___variant___create_embed', function (e) {
        var variant = new Variant({
            'id': UUID.generate(),
            'node_id': $('#node-id').val(),
            'content': ''
        });
        vc.variant_form_embed.load(variant, 1);
        vc.variant_form_embed.show();
        $('#variant-embed-content').focus();
    });

    $("body").on('click', '#script___variant___form_embed_hide', function (e) {
        vc.variant_form_embed.hide();
    });

    $("body").on('click', '.variant-create-button', function (e) {

        var variant = new Variant({
            'id': UUID.generate(),
            'node_id': $(this).closest('.node').data('id'),
            'content': ''
        });

        vc.variant_form.load(variant, 1);
        vc.variant_form.show();
    });
};