<?php  $this->registerJs("$('.public-footer h4').click(function () { $(this).next('ul').toggleClass('hide_mobile'); });"); ?>
<footer class="public-footer">
    <nav>
        <div class="row">
            <div class="col-xs-3">
                <a href="/" class="public-logo">
                    <img src="/static/logo/logo-<?= Yii::$app->params['division'] ?>_blunders.png">
                </a>
            </div>
            <div class="col-xs-3">
                <h4>О компании<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/blog">Блог / новости</a></li>
                    <li><a href="/blog/zvonki-bez-kosjakov-eto-vozmozhno">Как внедрить</a></li>
                    <li><a href="/#bens">Преимущества</a></li>
                    <li><a href="/#reg">Регистрация</a></li>
                    <li><a href="/rates">Тарифы</a></li>
                    <li><a href="/contact">Контакты</a></li>
                </ul>
            </div>
            <div class="col-xs-3">
                <h4>Помощь<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/blog/faq-voprosy">FAQ</a></li>
                    <li><a href="/support">Поддержка</a></li>
                    <li><a href="/blog/shablony-skriptov-dostupnye-dla-skacivania">Примеры скриптов</a></li>
                </ul>

                <h4>Партнеры<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/blog/razrabotka-skriptov-prodaz">Написание скриптов</a></li>
                    <li><a href="/blog/kak-uvelichit-prodazhi-za-schet-korporativnogo-obuchenia">Повышение продаж</a></li>
                    <li><a href="/blog/sem-sposobov-poluchit-vygodu-so-script-designer">Как стать партнером</a></li>
                </ul>
            </div>
            <div class="col-xs-3">
                <h4>Дополнительно<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/offer">Договор-оферта</a></li>
                </ul>


                <h4>Интеграции<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/blog/integracia-scriptdesigner-s-crm-sistemoj-rekomendacii ">CRM системы</a></li>
                    <li><a href="/blog/kak-sokratit-vremya-na-kontrol-telefonnyh-razgovorov-menedzherov">Телефония</a></li>
                </ul>
            </div>

        </div>
    </nav>
    <p class="copyright">Все права защищены (с) <?= date('Y') ?> дочерний проект B2B basis, <?php echo Yii::$app->params['phone'] ?></p>
<script type='text/javascript' async defer src='https://scriptdesigner.push4site.com/sdk'></script>
</footer>
