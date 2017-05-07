/**
 * Фабрика действий
 */
var CommandFactory = {};

/**
 * Инстанцирует объект команды
 *
 * @param {{}} data Данные для конструктора
 * @returns {Command}
 */
CommandFactory.getInstance = function (data) {

    if (!data['model_class']) {
        throw new Error('Для получения экземпляра команды необходимо наличие элемента model_class');
    }

    if (!data['model_id']) {
        throw new Error('Для получения экземпляра команды необходимо наличие элемента model_class');
    }

    return new Command(data);
};