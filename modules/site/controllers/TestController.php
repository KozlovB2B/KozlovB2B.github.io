<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 25.02.16
 * Time: 19:29
 */

namespace app\modules\site\controllers;


class TestController extends \yii\web\Controller
{

    public function actionIndex(){

        $next_month = mktime(0, 0, 0, date("m") + 1, date('d'), date("Y"));
//        $next_month = mktime(0, 0, 0, '12' + 1, '01', '2016');

        var_dump(date('d-m-Y H:i:s', $next_month));

    }

}