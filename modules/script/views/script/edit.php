<?php

/**
 * @var $this yii\web\View
 * @var $model app\modules\script\models\ar\Script
 * @var $hm app\modules\user\models\UserHeadManager;
 * @var $session app\modules\script\models\ar\EditorSession
 * @var $default_editor_options string
 */

use app\modules\script\components\assets\EditorAsset;
use app\modules\script\components\assets\EditorAssetProd;
use app\modules\script\components\WSConnection;
use app\modules\script\models\Call;
use yii\widgets\PjaxAsset;

PjaxAsset::register($this);


$this->title = $model->name;

if (YII_ENV == 'prod') {
    EditorAssetProd::register($this);
} else {
    EditorAsset::register($this);
}


$node_form_id = 'editor___node__form';
$group_form_id = 'editor___group__form';
$script_form_id = 'editor___script__form';
$variant_form_id = 'editor___variant__form';
$variant_form_embed_id = 'editor___variant__form_embed';
$group_variant_form_id = 'editor___group_variant__form';
$group_variant_form_embed_id = 'editor___group_variant__form_embed';

?>
<?php $this->registerJs("
$('#page-title').remove();
$('#main-navigation-func-panel').parent().remove();
$('#func-panel').addClass('editor');

Yiij.app.setModule('editor', {
    'constructor' : Editor,
    'debug': true,
    'request' : {
        '_csrf': '" . Yii::$app->getRequest()->csrfToken . "',
    },
    'stages' : " . json_encode(Call::getStages()) . ",
    'group_form' : {
        'id': '" . $group_form_id . "',
    },
    'node_form' : {
        'id': '" . $node_form_id . "',
    },
    'variant_form' : {
        'id': '" . $variant_form_id . "',
    },
    'variant_form_embed' : {
        'id': '" . $variant_form_embed_id . "',
    },
    'group_variant_form' : {
        'id': '" . $group_variant_form_id . "',
    },
     'group_variant_form_embed' : {
        'id': '" . $group_variant_form_embed_id . "',
    },
    'script_form' : {
        'id': '" . $script_form_id . "',
    },
    'session' : " . json_encode($session->getAttributes()) . ",
    'ws' : " . WSConnection::getJsConfig($model->id) . ",
    'culmann': {'coordinator' : 'editor___culmann_coordinator', 'paper' : 'editor___culmann_paper', 'container' : 'editor___culmann_container'},
    'data': " . $model->getBuild() . ",
    'create_builds_manually': " . ($hm->create_builds_manually ? "true" : "false") . ",
    'options': " . ($default_editor_options ? $default_editor_options : "null") . "
});

Yiij.app.getModule('editor').start();
");

if (isset($focus_node) && $focus_node) {
    $this->registerJs("Yiij.app.getModule('editor').culmann.focus($focus_node);");
}

?>


    <div id="editor___culmann_container" class="culmann-surface" style="transform: matrix(1, 0, 0, 1, 0, 0); transform-origin: 50% 50% 0px; transition: none;">
        <div class="culmann-surface-paper" id="editor___culmann_paper">
            <div class="culmann-surface-coordinator" id="editor___culmann_coordinator">
                <div id="new-object" class="new-object" style="display: none"></div>
                <div id="ruler-x" class="ruler-x" style="display: none"></div>
                <div id="ruler-y" class="ruler-y" style="display: none"></div>

                <div id="area-visualization" style="display: none; position: absolute;background: rgba(0,0,0, 0.03);border: 1px dashed black">area</div>
            </div>
        </div>
    </div>

    <div id="zoom-panel">

    </div>

    <div id="node_content_max_height_style_elem" style="display: none">
        <!--        <style>.node .node-content { max-height: 900px; }</style>-->
    </div>
<?php
// Модалы вставляются перед закрывающим тегом </body>
Yii::$app->controller->modals[] = $this->render("/node/_form_modal", ['form_id' => $node_form_id, 'variant_form_embed_id' => $variant_form_embed_id]);
Yii::$app->controller->modals[] = $this->render("/group/_form_modal", ['form_id' => $group_form_id, 'group_variant_form_embed_id' => $group_variant_form_embed_id]);
Yii::$app->controller->modals[] = $this->render("/variant/_form_modal", ['form_id' => $variant_form_id]);
Yii::$app->controller->modals[] = $this->render("/group-variant/_form_modal", ['form_id' => $group_variant_form_id]);
Yii::$app->controller->modals[] = $this->render("_form_modal", ['form_id' => $script_form_id]);

