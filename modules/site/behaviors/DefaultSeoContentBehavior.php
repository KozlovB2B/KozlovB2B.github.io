<?php

namespace app\modules\site\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class DefaultSeoContentBehavior
 *
 * @property \yii\web\View $owner
 */
class DefaultSeoContentBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);

        Yii::$app->getModule('site');

        if (empty($this->owner->title)) {
            $this->owner->title = static::getContentFor('title');
        }

        if (empty($this->owner->metaTags['description'])) {
            $this->owner->registerMetaTag(['name' => 'description', 'content' => static::getContentFor('description')], 'description');
        }

        if (empty($this->owner->metaTags['keywords'])) {
            $this->owner->registerMetaTag(['name' => 'keywords', 'content' => static::getContentFor('keywords')], 'keywords');
        }
    }

    /**
     * Get content for tag
     *
     * @param $tag
     * @return null|string
     */
    public static function getContentFor($tag)
    {
        $result = null;

        switch ($tag) {
            case 'title':
                $result = Yii::t('site', 'Sales Script PROMPTER - software and templates for cold calling and incoming phone calls');
                break;
            case 'description':
                $result = Yii::t('site', "Рowerful software engine will make your phone calling process easy & faultless. Get ready to use templates or develop your own sales scripts >>>>");
                break;
            case 'keywords':
                $result = Yii::t('site', "sales script software,  sales script, cold calling scripts, samples, templates, phone call scripts");
                break;
        }

        return $result;
    }
}