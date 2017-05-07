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
                    <li><a href="/">How to start</a></li>
                    <li><a href="/#bens">Features</a></li>
                    <li><a href="/rates">Pricing</a></li>
                    <li><a href="/support">Support</a></li>

                    <?php if (Yii::$app->getUser()->getIsGuest()) : ?>
                        <li><a href="/#">Log in</a></li>
                    <?php endif; ?>

                </ul>
            </nav>
        </div>
    </div>
</header>