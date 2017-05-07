<?php
use app\modules\site\components\LandingAssetBundle;

/* @var $this yii\web\View */
/** @var app\modules\user\models\HeadRegistrationForm $register */
/** @var app\modules\user\models\LoginForm $login */

$asset = LandingAssetBundle::register($this);
?>
        <div class="window-1" >
            <div class="container-fluid">
                <div class="row header">
                    <a href="/" class="logo">
                        <img src="/static/logo/logo-<?= Yii::$app->params['division'] ?>.png">
                    </a>
                    <nav class="nav">
                        <ul>
                            <li><a href="#why">Зачем</a></li>
                            <li><a href="#bens">Преимущества</a></li>
                            <li><a href="#reg">Регистрация</a></li>
                            <li><a href="/rates">Тарифы</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#signin">Вход</a></li>
                        </ul>
                    </nav>
                    <a class="phone" href="tel:<?php echo Yii::$app->params['phone_clean'] ?>"><?php echo Yii::$app->params['phone'] ?></a>
                    <div class="show_menu"><span></span><span></span><span></span></div>
                </div>

                <div class="row">
                    <div class="text-center">
                        <h1 class="heading">Конструктор скриптов продаж</h1>

                        <p class="lead">Ваши звонки без косяков</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4 text-cont">
                        <div class="heading-steps">
                            <span> Слушаем клиента</span><span class="dots">...........</span>
                            <br>
                            <br>
                            <span> Выбираем ответ</span><span class="dots">...........</span>
                            <br>
                            <br>
                            <span> Быстро отвечаем</span><span class="dots">...........</span>
                        </div>

                    </div>
                    <div class="col-xs-8 mac-cont">
                        Мы проектная организация. Наш Зам.директор Сергеева Наталья<br> Андреевна <br>хотела с вами встретиться и лично познакомиться, чтобы выяснить, чем мы можем быть друг другу полезны
                        <br>
                        <br>
                        Она готова встретится в (день недели и диапазон времени) вам когда удобней с ней встретиться?<br>Куда ей подъехать?
                        <div class="mac-button">
                            <span>Зачем с вами встречаться?</span>
                            <span>Нет времени встречаться</span>
                            <br>
                            <span>Называет время и дату</span>
                            <span>Нам это не надо / не интересно</span>
                            <br>
                            <span>В эти дни не смогу встретиться</span>
                            <span>У нас свои проектировщикам</span>
                            <span>Я подъеду к вам</span>
                            <br>
                            <span>Мы работаем с другими проектировщиками</span>
                            <span>Давайте созвонимся на следующей неделе</span>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="window-2" id="reg">
            <div class="text-center">
                <h2 class="heading">Ваши звонки без косяков</h2>
            </div>

            <div class="row">
                <?php echo $this->render('/site/_reg_form', ['register' => $register, 'id' => 'registration-form']); ?>
            </div>
        </div>


        <div class="window-3" id="why" style="background-image: url('<?= $asset->baseUrl ?>/img/window-3-bg-480.jpg')">
            <div class="text-center">
                <h2 class="heading">Зачем Вам нужен конструктор скриптов:</h2>
            </div>

            <div class="row">
                <div class="col-xs-1 col-xs-offset-1 icon">
                    <img src="<?= $asset->baseUrl ?>/img/w3-img3-flipped.png">
                </div>
                <div class="col-xs-10">
                    <p class="lined">-90% критических ошибок в телефонных переговорах</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1 col-xs-offset-1 icon">
                    <img src="<?= $asset->baseUrl ?>/img/w3-img1.png">
                </div>
                <div class="col-xs-10">
                    <p class="lined">100% рост конверсии за счет правильных сценариев разговоров</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1 col-xs-offset-1 icon">
                    <img src="<?= $asset->baseUrl ?>/img/w3-img2.png">
                </div>
                <div class="col-xs-10">
                    <p class="lined">В 2-4 раза быстрее обучение новичков в отделе продаж</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1 col-xs-offset-1 icon">
                    <img src="<?= $asset->baseUrl ?>/img/w3-img3.png">
                </div>
                <div class="col-xs-10">
                    <p class="lined">На 30% сокращение ФОТ и затрат на текучку</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1 col-xs-offset-1 icon">
                    <img style="height: 50px;opacity: 0.7;" src="<?= $asset->baseUrl ?>/img/w4-img3.png">
                </div>
                <div class="col-xs-10">
                    <p class="lined">Выявление ошибок в разговорах за 5-10 секунд без прослушивания</p>
                </div>
            </div>
        </div>


        <div class="window-4" id="bens">
            <div class="text-center">
                <h2 class="heading">Преимущества конструктора &laquo;Скрипт Дизайнер&raquo;</h2>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <div class='benefit-icon'>
                        <img src="<?= $asset->baseUrl ?>/img/w4-img2.png">
                    </div>
                    УДОБНО ПОЛЬЗОВАТЬСЯ<br>
                    МЕНЕДЖЕРАМ ПО ПРОДАЖАМ
                </div>
                <div class="col-xs-4">
                    <div class='benefit-icon'>
                        <img src="<?= $asset->baseUrl ?>/img/w4-img3.png">
                    </div>
                    БЫСТРО НАХОДИТЬ ОШИБКИ<br>
                    БЕЗ ПРОСЛУШИВАНИЯ
                </div>
                <div class="col-xs-4">
                    <div class='benefit-icon'>
                        <img src="<?= $asset->baseUrl ?>/img/w4-img1.png">
                    </div>
                    ЛЕГКО СОЗДАВАТЬ, ВНЕДРЯТЬ<br>
                    ИЗМЕНЯТЬ И ДОРАБАТЫВАТЬ
                </div>
            </div>
        </div>


        <div class="window-2">
            <div class="text-center">
                <h2 class="heading">Попробовать бесплатно</h2>
            </div>

            <div class="row">
                <?php echo $this->render('/site/_reg_form', ['register' => $register, 'id' => 'registration-form-2']); ?>
            </div>
        </div>

<?php

echo $this->render('/site/_login_modal', ['login' => $login]);
