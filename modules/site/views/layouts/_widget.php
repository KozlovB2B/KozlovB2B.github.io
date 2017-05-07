<?php
/* @var $this \yii\web\View */

$this->registerJs('
window["initialized_as_widget"] = false;

function PerformerMessageListener(event) {


    $("#site___head_dashboard__widget_usability_advise").removeClass("hide");

    if (typeof Yiij === "undefined") {
        return;
    }

    if(typeof event.data == "string" && event.data != "zero-timeout-message"){
        var data = JSON.parse(event.data);

        if (typeof data.page !== "undefined") {
            window["initialized_as_widget"] = true;

            if (Yiij.app && Yiij.app.getModule("performer")) {
                Yiij.app.getModule("performer").perform_page = data.page;
            }
        }

        if (typeof data.context !== "undefined") {
            if (Yiij.app && Yiij.app.getModule("context")) {
                Yiij.app.getModule("context").setData(data.context);
            }
        }
    }
}

window.addEventListener ? window.addEventListener("message", PerformerMessageListener) : window.attachEvent("onmessage", PerformerMessageListener);

parent.postMessage(JSON.stringify({location:window.location.href}), "*");
');