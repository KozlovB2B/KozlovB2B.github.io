<?php
use yii\widgets\ListView;
use yii\helpers\Html;

/**
 * @var yii\data\ActiveDataProvider $data_provider
 */
?>
<style>
    .rate-name {
        color: #FF9C00;
        font-size: 20px;
    }

    .rate-price {
        color: #777;
        font-size: 18px;
        margin-bottom: 30px;
    }

    .rate-restriction {
        text-align: center;
        margin: 10px;
    }

    .rate {
        display: inline-block;
        margin: 0 2.5% 0 0;
        width: 22.6%;
        text-align: center;
        height: 300px;
        padding: 20px 0;
        vertical-align: top;
        overflow: hidden;
    }

    .rate-content {
        border: 2px solid #FF9C00;
        height: 250px;
        padding: 25px 10px 50px 10px;
    }

    .rate-content.default {
        border: 4px solid #4cae4c;
        margin-top: -1px;
        box-shadow: 0 0 10px rgba(0, 0, 0, .50);
        background-color: #FDFDFD;

    }

    .rate-content.default .rate-name {
        color: #4cae4c;
    }

    .rate:last-child {
        margin: 0;
    }
</style>
<?php echo Html::tag('div', Html::tag('h1', Yii::t('billing', "Find a plan that's right for you")), ['class' => 'public-page-heading']) ?>
<div class="row">
    <?php echo ListView::widget([
        'dataProvider' => $data_provider,
        'summary' => false,
        'itemOptions' => ['class' => 'rate'],
        'itemView' => function (\app\modules\billing\models\Rate $model) {
            return $this->render('_list_item', ['model' => $model]);
        },
    ]) ?>
</div>
<div class="row">
    <br/>
    <?php echo $this->render('sale-note'); ?>

    <p class="text-center">
        <?= Html::a(Yii::t('billing', 'Sign up'), '/#reg', ['class' => 'btn btn-success btn-lg']) ?>
    </p>
</div>
