<?php
use app\modules\site\components\InstructionAssetBundle;
use app\modules\site\models\EmailServices;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var app\modules\user\models\User $user */

InstructionAssetBundle::register($this);
$this->title = Yii::t('site', 'Script Designer');


?>

<?php if (!$user->isConfirmed): ?>
    <div class="alert alert-success text-center">
        <?php
        if ($service = EmailServices::recognizeService($user->email)):
            echo Yii::t('site', 'Please, prove you’re not a robot. Please check your {mailbox} and confirm.', ['mailbox' => Html::a($service->name, $service->url, ['target' => '_blank'])]);
        else :
            echo Yii::t('site', 'Please, prove you’re not a robot. Please check your mailbox and confirm.');
        endif;
        ?>
    </div>
<?php endif; ?>

<div class="site-index">
    <div class="container">
        <div class="row">
            <div class="col-lg-10">
                <h2>
                    <?php if (!empty($user->profile->name)) : ?>
                        <?php echo Yii::t('site', 'Hello') ?>, <?php echo $user->profile->name ?>!
                    <?php else : ?>
                        <?php echo Yii::t('site', 'Hello') ?>!
                    <?php endif; ?>
                </h2>
            </div>
        </div>
    </div>

    <?php echo $this->render('_manual') ?>
</div>