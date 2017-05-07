<?php
namespace app\modules\core\components;

use Yii;
use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * Class AjaxValidationTrait
 * @package app\modules\core\components
 */
trait AjaxValidationTrait
{
    /**
     * Оставлено для совместимости с нативной формой
     *
     * @param Model $model
     * @param boolean $end
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidation(Model $model, $end = true)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (!Yii::$app->response->data)
                Yii::$app->response->data = [];

            Yii::$app->response->data = ArrayHelper::merge(Yii::$app->response->data, ActiveForm::validate($model));

            if ($end) {
                Yii::$app->end();
            }
        }
    }

    /**
     * Аякс валидация многих моделей
     *
     * @param Model|Model[] $model
     * @param Model $attributes
     *
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidationMultiple($model, $attributes = null)
    {
        if (Yii::$app->request->isAjax) {

            if (is_array($model)) {
                $models = $model;
            } elseif ($attributes instanceof Model) {
                $models = func_get_args();
                $attributes = null;
            } else {
                $models = [$model];
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = [];

            /* @var $model Model */
            foreach ($models as $model) {
                if ($model->load(Yii::$app->request->post())) {
                    Yii::$app->response->data = ArrayHelper::merge(Yii::$app->response->data, ActiveForm::validate($model));
                }
            }

            Yii::$app->end();
        }
    }

    /**
     * Аякс валидация многих моделей
     *
     * @param Model[] $models
     * @param boolean $end
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidationTabular($models, $end = true)
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = [];


            /* @var $model Model */
            $i = 0;

            foreach ($models as $model) {
                if ($model->load(Yii::$app->request->post())) {
                    $errors = ActiveForm::validate($model);
                    $formName = strtolower($model->formName());
                    $result = [];
                    foreach ($errors as $id => $value) {
                        $tabularId = $formName . '-' . $i . str_replace($formName, '', $id);
                        $result[$tabularId] = $value;
                    }

                    Yii::$app->response->data = ArrayHelper::merge(Yii::$app->response->data, $result);
                }
                $i++;
            }

            if ($end) {
                Yii::$app->end();
            }
        }
    }


    /**
     * Проводит аяксовую валидацию модели или моделей
     *
     * @param Model|Model[] $models
     *
     * @throws \yii\base\ExitException
     */
    protected function ajaxValidation($models)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (!is_array($models)) {
                $models = [$models];
            }

            $result = [];

            /** @var Model $model */
            foreach ($models as $model) {
                if ($model->load(Yii::$app->request->post())) {
                    $result = ArrayHelper::merge($result, ActiveForm::validate($model));
                }
            }

            if ($result) {
                Yii::$app->response->data = ['errors' => $result];
                Yii::$app->end();
            }
        }
    }
}
