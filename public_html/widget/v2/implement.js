var load = true;
var init = true;

if (load) {
    // Если указать куда положить виджет он сразу подгрузит свои зависимости и инициализируется при загрузке

    window['SSWidgetInstance'] = 'sswidget';

    // Если вы хотите самостоятельно инициализировать виджет - не указывайте переменную window['SSWidgetInstance']
    // Только не забудьте подгрузить jQuery и jQuery UI

    (function (d, w) {
        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () {
                n.parentNode.insertBefore(s, n);
            };
        s.type = "text/javascript";
        s.async = true;
        s.src = "//scriptdesigner.ru/widget/v2/widget.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window);

} else if (init) {
    window['sswidget'].init()
} else {
    window['sswidget'].destroy()
}