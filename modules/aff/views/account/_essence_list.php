<?php
/**
 * @var app\modules\aff\models\Account $account
 * @var $this yii\web\View
 */

use yii\helpers\Html;

?>
<h4 class="text-center">
    Affiliate program
    <br/>
    <small>(essence of contract)</small>
</h4>
<ol>
    <li>When a visitor to your site clicks your affiliate link to our site and completes a purchase, you get a commission</li>
    <li>All new affiliates can earn commissions of up to <?php echo $account->getPercent() ?>% based on performance, with a cookie length of 180 days</li>
    <li>You may see and check all your affiliates at the "<?php echo Html::a("Attracted users", '/aff/attracted-users') ?>" page</li>
    <li>Sales Script PROMPTER pays affiliates monthly or upon request and when the commission balance is $30 or more</li>
    <li>Getting commission for yourself is not allowed</li>
    <li>The agreement and all the payments can be canceled if you do not attract any paid users during 6 months</li>
</ol>