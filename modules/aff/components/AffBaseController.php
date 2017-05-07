<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 02.03.16
 * Time: 21:19
 */

namespace app\modules\aff\components;


use app\modules\core\components\CoreController;

class AffBaseController extends CoreController
{
    public function init()
    {
        parent::init();
        $this->layout = "@app/modules/aff/views/layouts/main";
    }
}