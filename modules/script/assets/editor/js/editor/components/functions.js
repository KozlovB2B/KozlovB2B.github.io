String.prototype.fmt = function (hash) {
    var string = this, key;
    for (key in hash) string = string.replace(new RegExp('\\{' + key + '\\}', 'gm'), hash[key]);
    return string
};

/**
 * Проверка обязательных полей объекта
 * @param object
 * @param properties
 */
function checkRequired(object, properties) {
    for (var i in properties) {
        if (typeof object[properties[i]] === 'undefined' || object[properties[i]] === null) {
            throw new Error('В объекте ' + object.constructor.name + ' свойство ' + properties[i] + ' обязательно!');
        }
    }
}