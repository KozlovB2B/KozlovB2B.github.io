<?php

namespace app\modules\blog\controllers;

use app\modules\blog\models\TagPostSearch;
use app\modules\core\components\Publishable;
use Yii;
use app\modules\blog\models\Post;
use app\modules\blog\models\PostSearch;
use app\modules\blog\components\BlogController;
use romi45\findModelTrait\FindModelTrait;
use app\modules\blog\models\Tag;
use yii\web\Response;
use yii\web\NotFoundHttpException;


/**
 * PostController implements the CRUD actions for Post model.
 *
 * @method Post findModel($id, $class = null) see [[FindModelTrait::findModel()]] for more info
 */
class PostController extends BlogController
{
    use FindModelTrait;

    /**
     * @var string Model class name for findModel($id, $class = null)
     */
    protected $_modelClass = 'app\modules\blog\models\Post';

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionBlog($t = null)
    {
        $this->setPublicLayout();

        return $this->render('blog', [
            'postDataProvider' => (new PostSearch())->blogSearch($t)
        ]);
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess('blog___blog__admin');
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->setPublicLayout();

        return $this->render('view', [
            'model' => $this->findPostByIdOrFriendlyUrl($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAccess('blog___blog__admin');
        $model = new Post();

        $model->user_id = Yii::$app->getUser()->getId();

        $model->status_id = Publishable::STATUS_DRAFT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->checkAccess('blog___blog__admin');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $query
     * @return array
     */
    public function actionTags($query)
    {
        $this->checkAccess('blog___blog__admin');

        /** @var Tag[] $models */
        $models = Tag::find()->andFilterWhere(['like', 'name', urldecode($query)])->all();
        $items = [];

        foreach ($models as $model) {
            $items[] = ['name' => $model->name];
        }
        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $items;
    }


    /**
     * Finds post by id or friendly url
     *
     * @param integer $id
     * @return Post
     * @throws NotFoundHttpException
     */
    protected function findPostByIdOrFriendlyUrl($id)
    {
        $field = is_numeric($id) ? 'id' : 'friendly_url';
        $result = Post::find()->forCurrentDivision()->andWhere($field . '=:id', [':id' => $id])->one();

        if ($result === null) {
            throw new NotFoundHttpException('Post with id - ' . $id . ' not found!');
        }

        if ($field == 'id' && $result->friendly_url) {
            Yii::$app->end(301, $this->redirect($result->getUrl(), 301));
        }

        return $result;
    }
}
