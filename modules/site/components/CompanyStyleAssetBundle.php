<?php
namespace app\modules\site\components;

use yii\web\AssetBundle;

class CompanyStyleAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/modules/site/assets/company-style';

    public function faviconHtml()
    {
        $url = $this->baseUrl . '/logo';

        return '<link rel="apple-touch-icon" sizes="57x57" href="' . $url . '/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="' . $url . '/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="' . $url . '/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="' . $url . '/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="' . $url . '/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="' . $url . '/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="' . $url . '/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="' . $url . '/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="' . $url . '/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="' . $url . '/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="' . $url . '/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="' . $url . '/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="' . $url . '/favicon-16x16.png">
<link rel="manifest" href="' . $url . '/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="' . $url . '/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">';
    }
}