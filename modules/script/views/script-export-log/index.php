<?php
/* @var $this yii\web\View */
/* @var $data_provider yii\data\ActiveDataProvider */
/* @var $search app\modules\script\models\ScriptExportLog */

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\script\models\ScriptExportLog;
use yii\bootstrap\ActiveForm;

$this->title = 'Попытки экспорта скриптов';
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin([
    'action' => '/script/script-export-log/index',
    'layout' => 'inline',
    'method' => 'get',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]); ?>

<?= $form->field($search, 'type_id')->dropDownList([ScriptExportLog::TYPE_IMPORT => 'Импорт', ScriptExportLog::TYPE_EXPORT => 'Экспорт'], ['prompt' => '-- тип']) ?>

<?= Html::submitButton('Поиск', ['class' => 'btn btn-success']) ?>
<?=  Html::a('Экспорт в эксель', '/script/script-export-log/index?as_excel=1&' . Yii::$app->request->queryString, ['class' => 'btn btn-primary pull-right']); ?>

<?php ActiveForm::end(); ?>

<br/>
<br/>

<?php echo GridView::widget([
    'dataProvider' => $data_provider,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            "attribute" => "type_id",
            "format" => "html",
            "value" => function (\app\modules\script\models\ScriptExportLog $model) {
                $text = $model->type_id ==  ScriptExportLog::TYPE_IMPORT ? 'Импорт' : 'Экспорт';
                $badge = $model->type_id ==  ScriptExportLog::TYPE_IMPORT ? 'success' : 'info';

                return Html::tag('span', $text, ["class" => "label label-$badge"]);
            }],
        'user_id',
        'username',
        'ip',
        'script_id',
        'source_script_id',
        'script_name',
        [
            "attribute" => "success",
            "format" => "html",
            "value" => function (\app\modules\script\models\ScriptExportLog $model) {
                $text = $model->success ? 'успешно' : 'неудачно';
                $badge = $model->success ? 'success' : 'danger';

                return Html::tag('span', $text, ["class" => "label label-$badge"]);
            }],
        [
            'attribute' => 'created_at',
            'format' => 'datetime'
        ],
    ],
]);