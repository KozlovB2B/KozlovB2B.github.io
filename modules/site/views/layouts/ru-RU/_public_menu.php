<header class="public-header">
    <div class="row">
        <div class="col-xs-2">
            <a href="/" class="public-logo">
                <img src="/static/logo/logo-<?= Yii::$app->params['division'] ?>_blunders.png">
            </a>
        </div>
        <div class="col-xs-10">
            <nav class="public-nav">
                <ul>
                    <li><a href="/blog/konstruktor-scriptov-prodaz-video-instrukciya">Как работает</a></li>
                    <li><a href="/#bens">Преимущества</a></li>
                    <li><a href="/rates">Тарифы</a></li>
                    <li><a href="/support">Помощь</a></li>

                    <?php if (Yii::$app->getUser()->getIsGuest()) : ?>
                        <li><a href="/#">Войти</a></li>
                    <?php endif; ?>

                </ul>
            </nav>
        </div>
    </div>
</header>