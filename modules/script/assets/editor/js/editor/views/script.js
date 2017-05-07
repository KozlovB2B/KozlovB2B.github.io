/**
 * Представление
 *
 * @param config
 * @constructor
 */
var ScriptView = function (config) {

    this.template = null;

    this.id_prefix = 'script-';

    YiijBaseModel.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
ScriptView.prototype = Object.create(YiijBaseModel.prototype);
ScriptView.prototype.constructor = ScriptView;

/**
 * Получить DOM элемент скрипта по ID
 *
 * @returns {*|jQuery|HTMLElement}
 */
ScriptView.prototype.get = function (id) {
    var v = this;

    var script = $("#" + v.id_prefix + id);

    return script.length ? script : false;
};

/**
 * Создания DOM элемента на основе данных узла
 *
 * @returns {*|jQuery|HTMLElement}
 */
ScriptView.prototype.loadTemplate = function () {
    var nv = this;

    $.ajax({
        'type': 'get',
        'async': false,
        'url': '/editor/views/script/template.html?v=2'
    }).done(function (data) {
        nv.template = data;
    });
};

/**
 * Создания DOM элемента на основе данных скрипта
 *
 * @param {Script} script
 * @returns {*|jQuery|HTMLElement}
 */
ScriptView.prototype.create = function (script) {
    //var v = this;
    //
    if (this.template === null) {
        this.loadTemplate();
    }

    Yiij.trace('Добавляем DOM элемент для скрипта ' + script.id);


    var elem = $(this.template.fmt({
        'name': script.name,
        'id': script.id
    }));

    elem.attr('id', this.id_prefix + script.id);

    elem.data('id', script.id);

    Yiij.app.getModule('editor').panel.getElem().append(elem);

    new NodeSelect('editor___script__start_node_uuid');

    NodeSelects['editor___script__start_node_uuid'].selectize.setValue(script.start_node_uuid, true);
};


/**
 * Применяем изменения, которые произошли в модели скрипта к DOM элементу
 *
 * @param {Script} script
 */
ScriptView.prototype.applyChanges = function (script) {

    Yiij.trace('Вносим изменения в DOM элемент скрипта ' + script.id + ', если такие имеются...');

    var changes = 0;

    var name_field = $('#editor___script__name');
    var start_node_uuid_selectize = NodeSelects['editor___script__start_node_uuid'].selectize;

    if (name_field.val() != script.name) {
        Yiij.trace('Название скрипта изменилось c' + name_field.val() + ' на ' + script.name);
        name_field.val(script.name);

        changes++;
    }

    if (start_node_uuid_selectize.getValue() != script.start_node_uuid) {
        Yiij.trace('Начальный узел изменился c' + start_node_uuid_selectize.getValue() + ' на ' + script.start_node_uuid);
        start_node_uuid_selectize.setValue(script.start_node_uuid, true);

        changes++;
    }


    if (!Yiij.app.getModule('editor').create_builds_manually) {
        $('#editor___function__draft_new_release').hide();
    }

    Yiij.trace('В DOM элемент скрипта ' + script.id + ' внесено изменений: ' + changes);
};