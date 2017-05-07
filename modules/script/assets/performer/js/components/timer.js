/**
 * Call timer
 *
 * @constructor
 */
var Timer = function (config) {
    YiijBaseComponent.apply(this, [config]);
};


/**
 * Extends
 * @type {YiijBaseModel}
 */
Timer.prototype = Object.create(YiijBaseComponent.prototype);
Timer.prototype.constructor = Timer;

/**
 * Количество секунд, отмерянных таймером
 * @type {number}
 */
Timer.prototype.elapsed_seconds = 0;

/**
 * Количество секунд, отмерянных таймером
 * @type {number}
 */
Timer.prototype.interval = null;

/**
 * Куда показывать таймер
 * @type {*|jQuery|HTMLElement}
 */
Timer.prototype.output = null;

/**
 * Рисуте секундочки
 */
Timer.prototype.render = function () {

    function pretty_time_string(num) {
        return ( num < 10 ? "0" : "" ) + num;
    }

    var hours = Math.floor(this.elapsed_seconds / 3600);

    var output = (hours ? pretty_time_string(Math.floor(this.elapsed_seconds / 3600)) + ":" : "") + pretty_time_string(Math.floor((this.elapsed_seconds % 3600) / 60)) + ":" + pretty_time_string(Math.floor((this.elapsed_seconds % 3600) % 60));

    this.output.html(output);
};

/**
 * Стартует таймер
 */
Timer.prototype.start = function () {
    var c = this;

    this.elapsed_seconds = 0;

    this.interval = setInterval(function () {
        c.elapsed_seconds++;
        c.render();
    }, 1000);
};

/**
 * Stops the timer
 */
Timer.prototype.stop = function () {
    clearInterval(this.interval);
};
