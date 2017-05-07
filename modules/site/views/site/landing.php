<?php
use app\modules\site\components\LandingAssetBundle;

/* @var $this yii\web\View */
/** @var app\modules\user\models\HeadRegistrationForm $register */
/** @var app\modules\user\models\LoginForm; $login */

$asset = LandingAssetBundle::register($this);
?>

    <div class="window-1">
        <div class="container-fluid">
            <div class="row header">
                <a href="/" class="logo">
                    <img src="/static/logo/logo-<?= Yii::$app->params['division'] ?>.png">
                </a>
                <nav class="nav">
                    <ul>
                        <li><a href="#why" class="scrollto">Benefits</a></li>
                        <li><a href="#bens">Features</a></li>
                        <li><a href="#reg">Sign up</a></li>
                        <li><a href="/rates">Pricing</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#signin">Log in</a></li>
                    </ul>
                </nav>
            </div>

            <div class="row">
                <div class="text-center">
                    <h1 class="heading">Sales Script PROMPTER</h1>

                    <p class="lead">
                        <small>faultless easy calling, no blunders</small>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4 text-cont">
                    <div class="heading-steps">
                        <span>Listening</span><span class="dots">...........</span>
                        <br>
                        <br>
                        <span>Choosing</span><span class="dots">...........</span>
                        <br>
                        <br>
                        <span>Answering</span><span class="dots">...........</span>
                    </div>

                </div>
                <div class="col-xs-8 mac-cont">
                    <br/>
                    <br/>
                        <span class="biger-text">
                        Glad to hear that you received our email and maybe even checked out our website…
                        <br/>
                        <br/>
                        Anyway, do you have 30 seconds and I will tell you why I’m calling and you can see if it makes sense for us to talk.
                            </span>
                    <br/>
                    <br/>
                    <br/>
                    <br/>

                    <div class="mac-button biger">
                        <span>What about</span>
                        <span>Ok, tell me more</span>
                        <br>
                        <span>No, inconvenient time</span>
                        <span>Your proposal is not interesting</span>
                        <span>I don't need it</span>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="window-2" id="reg">
        <div class="text-center">
            <h2 class="heading">Try Script PROMPTER - FREE for 14 days</h2>
        </div>

        <div class="row">
            <?php echo $this->render('/site/_reg_form', ['register' => $register, 'id' => 'registration-form']); ?>
        </div>
    </div>

    <div class="window-3" id="why" style="background-image: url('<?= $asset->baseUrl ?>/img/window-3-bg-480.jpg')">
        <div class="text-center">
            <h2 class="heading">Script PROMPTER benefits:</h2>
        </div>

        <div class="row">
            <div class="col-xs-1 col-xs-offset-1 icon">
                <img src="<?= $asset->baseUrl ?>/img/w3-img3-flipped.png">
            </div>
            <div class="col-xs-10">
                <p class="lined">-90% calling mistakes and fuckups</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 col-xs-offset-1 icon">
                <img src="<?= $asset->baseUrl ?>/img/w3-img1.png">
            </div>
            <div class="col-xs-10">
                <p class="lined">+100% conversion rate increase by well-thought-out scripts</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 col-xs-offset-1 icon">
                <img src="<?= $asset->baseUrl ?>/img/w3-img2.png">
            </div>
            <div class="col-xs-10">
                <p class="lined">2 times faster and easy on-the-job trainings</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 col-xs-offset-1 icon">
                <img src="<?= $asset->baseUrl ?>/img/w3-img3.png">
            </div>
            <div class="col-xs-10">
                <p class="lined">30-50% less salaries and staff turnover</p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 col-xs-offset-1 icon">
                <img style="height: 50px;opacity: 0.7;" src="<?= $asset->baseUrl ?>/img/w4-img3.png">
            </div>
            <div class="col-xs-10">
                <p class="lined">5-10 seconds on faults detection without listening of voice recording</p>
            </div>
        </div>
    </div>


    <div class="window-4" id="bens">
        <div class="text-center">
            <h2 class="heading">Features that work for you</h2>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <div class='benefit-icon'>
                    <img src="<?= $asset->baseUrl ?>/img/w4-img2.png">
                </div>
                EASY TO USE FOR<br>
                TELESALES PEOPLE
            </div>
            <div class="col-xs-4">
                <div class='benefit-icon'>
                    <img src="<?= $asset->baseUrl ?>/img/w4-img3.png">
                </div>
                QUICK FAILURE ANALYSIS,<br>
                WITHOUT LISTENING
            </div>
            <div class="col-xs-4">
                <div class='benefit-icon'>
                    <img src="<?= $asset->baseUrl ?>/img/w4-img1.png">
                </div>
                EASY TO IMPLEMENT <br>
                AND IMPROVE
            </div>
        </div>
    </div>


    <div class="window-2">
        <div class="text-center">
            <h2 class="heading">Try Script PROMPTER - FREE for 14 days</h2>
        </div>

        <div class="row">
            <?php echo $this->render('/site/_reg_form', ['register' => $register, 'id' => 'registration-form-2']); ?>
        </div>
    </div>
<?php echo $this->render('/site/_login_modal', ['login' => $login]);