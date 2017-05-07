/**
 * Объект-обертка для функций вкладки
 * @param id
 * @constructor
 */
var SSChromePanelTab = function (id) {
    var t = this;
    t.id = id;
    t.init();
};

/**
 * Скрипт показывающий/скрывающий панельку
 */
SSChromePanelTab.prototype.executeToggleScript = function () {
    chrome.tabs.executeScript(this.id, {file: "functions/toggle.js"});
};

/**
 * Инициализация виджета во вкладке
 */
SSChromePanelTab.prototype.init = function () {
    var t = this;
    t.executeToggleScript();
};

/**
 * Инспектор, следящий за всеми объектами вкладок
 *
 * @constructor
 */
var SSChromePanelInspector = function () {
    var i = this;

    i.tabs = {};
};

/**
 * Функция-обработчик клика по кнопке расширения
 *
 * @param tab_id
 */
SSChromePanelInspector.prototype.toggle = function (tab_id) {
    var i = this;

    if (typeof i.tabs[tab_id] === 'undefined') {
        i.tabs[tab_id] = new SSChromePanelTab(tab_id);
    } else {
        i.tabs[tab_id].executeToggleScript();
    }
};

// Инициализируем инспектор панелей
var sschromepanelinspector = new SSChromePanelInspector();

// Инициализируем обработчик клика по кнопке расширения
chrome.browserAction.onClicked.addListener(function (tab) {
    sschromepanelinspector.toggle(tab.id);
});

chrome.tabs.onUpdated.addListener( function (tabId, changeInfo, tab) {
    if (changeInfo.status == 'complete') {
        chrome.tabs.executeScript(tabId, {file: "functions/auto_init.js"});
    }
});