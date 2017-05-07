<?php

namespace app\modules\script\components\assets;

use yii\web\AssetBundle;
use Yii;
use MatthiasMullie\Minify;

/**
 * Class ScriptEditorAsset
 */
class EditorAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/script/assets/editor';

    public function init()
    {
        parent::init();

        $path = Yii::getAlias($this->sourcePath);
        $minifier = new Minify\JS();
        foreach($this->js as $j){
            $minifier->add($path.'/'.$j);
        }

        $minifier->minify(Yii::getAlias('@app/modules/script/assets/editor-prod').'/mywhalalla.js');
        file_put_contents(\Yii::getAlias('@app/modules/script/assets/editor-prod').'/mywhalalla.css', file_get_contents($path.'/css/editor.css'));
    }

    public $css = [
        'css/editor.css'
    ];


    public $js = [
        'js/libs/selectize-plugin-clear.js',
        'js/libs/jquery-ui.min.js',
        'js/libs/jsplumb2.js',
        'js/libs/svgjs.js',


        'js/editor/commands/command.js',

        'js/editor/components/func-panel/panel.js',
        'js/editor/components/func-panel/search.js',
        'js/editor/components/func-panel/button.js',
        'js/editor/components/func-panel/buttons/create-group.js',
        'js/editor/components/func-panel/buttons/undo.js',
        'js/editor/components/func-panel/buttons/redo.js',
        'js/editor/components/func-panel/buttons/create-node.js',
        'js/editor/components/func-panel/buttons/zoom-in.js',
        'js/editor/components/func-panel/buttons/zoom-fit.js',
        'js/editor/components/func-panel/buttons/zoom-out.js',


        'js/editor/components/command-factory.js',
        'js/editor/components/command-invoker.js',
        'js/editor/components/node-select.js',
        'js/editor/components/group-select.js',
        'js/editor/components/culmann.js',
        'js/editor/components/session.js',
        'js/editor/components/web-socket.js',
        'js/editor/components/request.js',
        'js/editor/components/messenger.js',
        'js/editor/components/functions.js',
        'js/editor/components/relations-manager.js',
        'js/editor/components/image-exporter.js',


        'js/editor/controllers/stage.js',
        'js/editor/controllers/script.js',
        'js/editor/controllers/group.js',
        'js/editor/controllers/group-variant.js',
        'js/editor/controllers/node.js',
        'js/editor/controllers/node-clone.js',
        'js/editor/controllers/variant.js',

        'js/editor/models/script.js',
        'js/editor/models/group.js',
        'js/editor/models/group-form.js',
        'js/editor/models/group-variant.js',
        'js/editor/models/group-variant-form.js',
        'js/editor/models/node.js',
        'js/editor/models/node-clone.js',
        'js/editor/models/node-form.js',
        'js/editor/models/variant-form.js',
        'js/editor/models/variant-form-embed.js',
        'js/editor/models/group-variant-form-embed.js',
        'js/editor/models/variants-sortable-list.js',
        'js/editor/models/group-variants-sortable-list.js',
        'js/editor/models/script-form.js',
        'js/editor/models/variant.js',


        'js/editor/views/script.js',
        'js/editor/views/group.js',
        'js/editor/views/group-variant.js',
        'js/editor/views/node.js',
        'js/editor/views/node-clone.js',
        'js/editor/views/variant.js',

        'js/editor/editor.js'
    ];

    public $depends = [
        'app\modules\site\components\assets\V2DesignAsset',
        'app\modules\core\components\assets\SelectizeAsset',
        'app\modules\script\components\assets\MousewheelAsset',
        'app\modules\script\components\assets\PanZoomAsset',
        'app\modules\site\components\assets\CentrifugeAsset',
        'app\modules\core\components\assets\JsToolsAsset',
        'app\modules\core\components\assets\QTipAsset',
        'app\modules\core\components\assets\WysiAsset',
    ];
}