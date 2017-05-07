/**
 * Контроллер клона узла
 *
 * @param config
 * @constructor
 */
var NodeCloneController = function (config) {
    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
NodeCloneController.prototype = Object.create(YiijBaseController.prototype);
NodeCloneController.prototype.constructor = NodeCloneController;

/**
 * Объект, отвечающий за представление узлов
 *
 * @type {NodeCloneView}
 */
NodeCloneController.prototype.view;

/**
 * @type {{}}
 */
NodeCloneController.prototype.clones = {};

/**
 * @inheritdoc
 */
NodeCloneController.prototype.requiredConfig = function () {
    return ['view'];
};


/**
 * Инициализация контроллера клонов узлов
 */
NodeCloneController.prototype.init = function () {
    Yiij.trace('Инициализация контроллера клонов узлов.');
    this.setEvents();
};

/**
 * Найти или создать узел
 *
 * @param id
 * @param {{}} data
 * @returns {NodeClone}
 */
NodeCloneController.prototype.getOrCreate = function (id, data) {
    Yiij.trace('Найти или создать узел ' + id + '.');

    if (typeof this.clones[id] === 'undefined') {
        this.clones[id] = new NodeClone(data);
    }

    return this.clones[id];
};

/**
 * Отображение узла
 *
 * @param {NodeClone} n
 */
NodeCloneController.prototype.setEvents = function (n) {

    $("body").on('click', '.node-duplicate-button', function (e) {

        var button = $(this);

        if(button.hasClass('disabled')){
            return false;
        }

        button.addClass('disabled');

        var source_id = $(this).closest('.node').data('id');

        var script_id = Yiij.app.getModule('editor').scriptController.script.id;

        function cloneCB(number) {
            var source = Yiij.app.getModule('editor').nodeController.get(source_id);

            var target = JSON.parse(JSON.stringify(source));

            target.id = UUID.generate();
            target.number = number;
            target.top += 50;
            target.left += 50;
            target.variants_sort_index = '';

            var to_data = {
                'node': target,
                'variants': []
            };

            var variants = Yiij.app.getModule('editor').variantController.nodeList(source_id);

            var variant_created = timestamp();

            for (var p = 0; p < variants.length; p++) {
                variant_created++;
                var variant = JSON.parse(JSON.stringify(variants[p]));
                variant.id = UUID.generate();
                variant.target_id = null;
                variant.node_id = target.id;
                variant.created_at = variant_created;
                to_data.variants.push(variant);
            }

            var clone = {
                'id': UUID.generate(),
                'script_id': script_id,
                'from': source.id,
                'to': target.id,
                'to_data': JSON.stringify(to_data),
                'created_at': timestamp(),
                'deleted_at': null
            };

            var clone_rollback = JSON.parse(JSON.stringify(clone));

            clone_rollback.deleted_at = timestamp();

            Yiij.app.getModule('editor').create({
                'model_class': 'NodeClone',
                'model_id': clone.id,
                'p': clone,
                'r': clone_rollback
            });

            button.removeClass('disabled');
        }


        Yiij.trace('Получение номера для клонированного узла...');

        $.get({
            'url': '/script/editor/node-number?script_id=' + script_id,
            'success': function (res) {
                Yiij.trace('Номер клонированного узла: ' + res);
                cloneCB(res);
            }
        });
    });
};

