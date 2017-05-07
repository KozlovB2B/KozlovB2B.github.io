<?php

namespace app\modules\site\controllers;

use app\modules\billing\models\Account;
use app\modules\script\models\ar\Script;
use app\modules\user\components\AmoLeadCreator;
use app\modules\user\models\LoginForm;
use app\modules\user\models\PasswordRecoveryForm;
use app\modules\user\models\profile\Designer;
use app\modules\user\models\User;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\HeadRegistrationForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
//use app\web\Controller;
use yii\filters\VerbFilter;
use app\modules\core\components\CoreController;
use app\modules\site\models\ContactForm;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class SiteController extends CoreController
{
    // для битрикса
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Is user logged in
     */
    public function actionAmILoggedIn()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            $this->result('no');
        } else {
            $this->result('yes');
        }
    }

    /**
     * Is user logged in
     */
    public function actionTest()
    {
        $this->result('ok');
    }

    /**
     * Is user logged in
     */
    public function actionTestTime()
    {
        echo date_default_timezone_get() . "<br>\n";;
        echo "Time: " . date("Y-m-d H:i:s") . "<br>\n";

        $shortName = exec('date +%Z');
        echo "Short timezone:" . $shortName . "<br>";

        $longName = timezone_name_from_abbr($shortName);
        echo "Long timezone:" . $longName . "<br>";

        date_default_timezone_set($longName);
        echo "Time: " . date("Y-m-d H:i:s") . "<br>\n";
    }


    public function actionIndex()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            /** @var HeadRegistrationForm $register */
            $register = Yii::createObject(HeadRegistrationForm::className());
            $login = Yii::createObject(LoginForm::className());

            $this->layout = "@app/modules/site/views/layouts/landing";

            return $this->render('landing', compact('register', 'login'));
        }

        if (Yii::$app->getUser()->can("admin")) {
            return $this->indexAdmin();
        } elseif (Yii::$app->getUser()->can("user_head_manager")) {
            return $this->redirect(Url::to(["/site/site/head-dashboard"]));
        } elseif (Yii::$app->getUser()->can("user___designer__dashboard")) {
            return $this->redirect(Url::to(["/site/site/designer-dashboard"]));
        } elseif (Yii::$app->getUser()->can("user_operator")) {
            return $this->redirect(Url::to(["/site/site/operator-dashboard"]));
        }

        return null;
    }

    /**
     * @return \yii\web\Response
     */
    public function actionWidget()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            return $this->redirect('/login');
        } else {
            return $this->redirect('/');
        }
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionWelcome()
    {
        if (Yii::$app->getUser()->can("user_head_manager")) {
            return $this->actionHeadDashboard();
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @return string
     */
    public function actionHeadDashboard()
    {
        $this->checkAccess('user___head__dashboard');
        if (Account::isBlocked()) {
            $this->redirect('/billing');
        }

        $not_converted = new ActiveDataProvider([
            'query' => Script::find()->byAccount(Yii::$app->getUser()->getId())->notConverted(),
        ]);

        if ($not_converted->getTotalCount()) {
            return $this->redirect('/conversion');
        }

        $scripts_data_provider = new ActiveDataProvider([
            'query' => Script::find()->activeByUserCriteria(Yii::$app->getUser()->getId()),
        ]);

        return $this->render('index_head', ['scripts_data_provider' => $scripts_data_provider]);
    }

    /**
     * @return string
     */
    public function actionDesignerDashboard()
    {
        $this->checkAccess('user___designer__dashboard');

        if (Account::isBlocked(Designer::current()->head_id)) {
            return $this->render('index_blocked');
        }

        $scripts_data_provider = new ActiveDataProvider([
            'query' => Script::find()->activeByUserCriteria(Designer::current()->head_id),
        ]);

        return $this->render('index_designer', ['scripts_data_provider' => $scripts_data_provider]);
    }


    /**
     * @return string
     */
    public function actionManual()
    {
        $this->checkAccess('user___head__dashboard');

        if (Account::isBlocked()) {
            $this->redirect('/billing');
        }

        return $this->render('manual_head');
    }


    public function actionOperatorDashboard()
    {
        $this->checkAccess('user___operator__dashboard');

        if (Account::isBlocked(Operator::current()->head_id)) {
            return $this->render('index_blocked');
        }

        return $this->render('index_operator');
    }


    protected function indexAdmin()
    {

        return $this->render('index_admin');
    }

    /**
     * Finds the Operator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findOperatorModel($id)
    {
        if (($model = Operator::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested operator does not exist.');
        }
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionFlash()
    {
        return $this->renderPartial("_flash");
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionOffer()
    {
        $this->setCleanLayout();

        return $this->render('offer');
    }

    public function actionFaq()
    {
        $this->setPublicLayout();
        return $this->render('faq');
    }

    public function actionSupport()
    {
        $this->setPublicLayout();
        return $this->render('support');
    }

    public function actionContact()
    {
        $this->setPublicLayout();
        return $this->render('contact');
    }

    public function actionContactModal()
    {
        return $this->renderAjax('_contact_modal');
    }

    public function actionTime()
    {
        return $this->result(time());
    }

    public function actionWs()
    {
        return $this->result(time());
    }
}
