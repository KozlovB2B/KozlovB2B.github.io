<?php

use rmrevin\yii\fontawesome\FA;
use yii\helpers\ArrayHelper;


/**
 * @var app\modules\integration\models\Integration $integration
 * @var yii\web\View $this
 */

$logoAsset = $this->registerAssetBundle($integration->logoAsset);

?>
    <a href="/integration/<?php echo $integration->id ?>" class="integration___item">
        <img class="img-responsive" src="<?= $logoAsset->baseUrl ?>/img/logo-300x300.png">
        <h2>
            <?php echo $integration->name ?>
        </h2>
    </a>


