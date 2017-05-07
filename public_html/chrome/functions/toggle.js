var loaded = document.getElementById('sswidget-loaded');

function getIsSSWidgetDestryed() {
    var from_storage = localStorage.getItem('sswidget');

    if (from_storage) {
        var data = JSON.parse(from_storage);
        return data.destroyed;
    }

    return false;
}

if (!loaded) {
    var elt = document.createElement("script");
    elt.innerHTML = "window['SSWidgetInstance'] = 'sswidget';";
    document.head.appendChild(elt);

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

} else if (getIsSSWidgetDestryed()) {
    var elt = document.createElement("script");
    elt.innerHTML = "window['sswidget'].init()";
    document.head.appendChild(elt);
} else {
    var elt = document.createElement("script");
    elt.innerHTML = "window['sswidget'].destroy()";
    document.head.appendChild(elt);
}