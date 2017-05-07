<?php
use app\modules\script\models\Call;
use app\modules\script\components\ScreenAssetBundle;
use app\modules\core\components\CoreAssetBundle;

use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAssetBundle;


FontAwesomeAssetBundle::register($this);
CoreAssetBundle::register($this);
ScreenAssetBundle::register($this);


/* @var $model app\modules\script\models\Script */
$print_dimensions = $model->printDimensions();
$data_json = $model->data_json ? $model->data_json : "{}";
$this->registerJs("new ScriptRenderer(" . $model->id . ", " . $data_json . ", " . json_encode(Call::getStages()) . ");");
?>
    <div id="script___designer__main_container" style="padding: 50px;">
        <div id="script___designer__canvas" style="width: <?php echo $print_dimensions['w'] ?>px; height: <?php echo $print_dimensions['h'] ?>px; border: none"></div>
    </div>

<?= $this->render("_templates"); ?>