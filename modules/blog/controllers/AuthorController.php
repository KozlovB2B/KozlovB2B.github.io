<?php

namespace app\modules\blog\controllers;

use Yii;
use app\modules\blog\models\Author;
use app\modules\blog\models\AuthorSearch;
use app\modules\blog\components\BlogController;
use romi45\findModelTrait\FindModelTrait;
use yii\web\UploadedFile;

/**
 * AuthorController implements the CRUD actions for Author model.
 *
 * @method Author findModel($id, $class = null) see [[FindModelTrait::findModel()]] for more info
 */
class AuthorController extends BlogController
{
    use FindModelTrait;

    /**
     * @var string Model class name for findModel($id, $class = null)
     */
    protected $_modelClass = 'app\modules\blog\models\Author';

    /**
     * Lists all Author models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess('blog___blog__admin');

        return $this->render('index', [
            'dataProvider' => (new AuthorSearch())->search(),
        ]);
    }

    /**
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAccess('blog___blog__admin');
        $model = new Author();
        $model->setScenario('insert');

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $model->avatar_file = UploadedFile::getInstance($model, 'avatar_file');

            if ($model->avatar_file && $model->validate()) {
                $model->avatar = '/uploads/author/' . uniqid() . '.' . $model->avatar_file->extension;
                $model->avatar_file->saveAs(Yii::getAlias('@webroot') . $model->avatar);
                $model->save();

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->checkAccess('blog___blog__admin');
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->avatar_file = UploadedFile::getInstance($model, 'avatar_file');

            if ($model->avatar_file) {
                $model->avatar = '/uploads/author/' . uniqid() . '.' . $model->avatar_file->extension;
                $model->avatar_file->saveAs(Yii::getAlias('@webroot') . $model->avatar);
            }

            $model->save();

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Author model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkAccess('blog___blog__admin');
        $this->findModel($id)->safeDelete();

        return $this->redirect(['index']);
    }
}
