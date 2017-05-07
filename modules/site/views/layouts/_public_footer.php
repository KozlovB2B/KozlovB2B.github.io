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
                <h4>About us<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/blog">News</a></li>
                    <li><a href="/">How it works</a></li>
                    <li><a href="/#bens">Benefits</a></li>
                    <li><a href="/#reg">Try 14 days free</a></li>
                    <li><a href="/rates">Pricing</a></li>
                    <li><a href="/contact">Contact us</a></li>
                </ul>
            </div>
            <div class="col-xs-3">
                <h4>Support<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/support">Technical support</a></li>
                </ul>

                <h4>Partnership<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/">Script development</a></li>
                    <li><a href="/">How to become a partner</a></li>
                    <li><a href="/">Affiliate program</a></li>
                </ul>
            </div>
            <div class="col-xs-3">
                <h4>In addition<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/offer">Terms</a></li>
                    <li><a href="/offer">Security statement </a></li>
                </ul>

                <h4>API & Tech<span>&#10095;</span></h4>
                <ul class="hide_mobile">
                    <li><a href="/">CRM integrations</a></li>
                    <li><a href="/">Telephony</a></li>
                </ul>
            </div>

        </div>
    </nav>
    <p class="copyright">All rights reserved (с) <?= date('Y') ?> subsidiary company of B2B basis</p>

</footer>