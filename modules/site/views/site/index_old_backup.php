<?php

/* @var $this yii\web\View */
/* @var app\modules\user\models\User $user */

$this->title = Yii::t('site', 'Script Designer');
?>

<div class="site-index">
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <h2>
                <?php if (!empty($user->profile->name)) : ?>
                    Добрый День, <?php echo $user->profile->name ?>!
                <?php else : ?>
                    Добрый День!
                <?php endif; ?>
            </h2>


            <h3>Наиболее часто встречающиеся вопросы по сервису ScriptDesigner:</h3>

            <?php

            $instruction_data = [

                [
                    'heading' => 'Как создать скрипт',
                    'content' => "
<strong>Вариант 1. Использовать шаблон скрипта</strong>
<ul>
    <li>1.Скачайте шаблоны скриптов с расширением .SCRD здесь b2bbasis.info/script (в статьях есть ссылки на файлы скриптов) или здесь  <a href='https://www.facebook.com/groups/ScriptDesigner' target='_blank'>https://www.facebook.com/groups/ScriptDesigner</a>.</li>
    <li>
    2.Зайдите в раздел меню «Скрипты» и выпадающий раздел «Редактирование».<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image001.png'>
    </li>
    <li>
    3. Нажмите на кнопку «Импортировать из файла» вверхнем левом углу<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image003.png'>
    </li>
    <li>
    4. Нажмите кнопку «Обзор»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image005.png'>
    </li>
    <li>
    5. Найдите файл с шаблоном скрипта, выберете его и нажмите «Открыть»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image007.png'>
    </li>
    <li>
    6. В строке файл для импорта появиться строка с описанием пути к файлу. Нажмите кнопку «Импорт». <br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image009.png'>
    </li>
    <li>
    7. Откроется дерево заруженного скрипта. Каждый скрипт состоит из узлов, которые соединены друг с другом связями.
    В узел входит фраза, которую произносит менеджер (верхняя синяя часть узла) и варианты ответов клиента (зеленые строки под фразой менеджера). Каждый вариант ответа клиента связан с фразой менеджера (другим узлом).
    <br/>
    Чтобы сделать скрипт крупнее  покрутите колечко мышки.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image011.png'>
    </li>
    <li>
    8. Выберите узел для внесения корректив и нажмите изображение карандаша (выделено оранжевым цветом с подсказкой «Редактировать содержимое».<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image013.png'>
    </li>
    <li>
    9. Откроется окна с узлом скрипта. Внесите коррективы в текст узла с помощью функции текстового редактора и нажмите кнопку «ОК»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image015.png'>
    </li>
    <li>
    10. Для того, чтобы добавить дополнительные вариант ответа нажмите на поле «+Добавить ответ»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image017.png'>
    </li>
    <li>
    11. Откроется окно варианта ответа. Внесите вариант ответа и нажмите кнопку «ОК».<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image019.png'>
    </li>
    <li>
    12. Новый вариант ответа будет добавлен к существующим ответам в узле. Отредактировать ответ клиента можно, нажав на карандаш справа от текст, удалить ответ  - нажав на крест слева от текста.
    </li>
    <li>
    13. Для создания нового узла захватите мышью кнопку «Добавить узел» и потяните вправо - откроется окно.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image021.png'>
    </li>
    <li>
    14. Впишите в него фразу менеджера. <br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image025.png'>
    </li>
    <li>
    15. Выберите этап разговора из списка (для удобства привязки узлов к этапам).<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image027.png'>
    </li>
    <li>
    16. Название этапа отобразиться в строке и нажмите «ОК».<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image029.png'>
    </li>
    <li>
    17. Новый узел появиться на экране. Для того, чтобы соединить его с вариантом ответа клиента в другом узле, нажмите на центр строки с ответом клиента и потяните красную линию к нужному узлу.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image031.png'>
    </li>
    <li>
    18. Подтяните красную линию к верхней части узла до момента, как синия часть станет зеленой. Это значит, что связь между узлами установлена.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image033.png'>
    </li>
    <li>
    19. Выберите в строке Начало (слева над кнопкой «Сохранить») из списка узел, с которого должен стартовать скрипт.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image035.png'>
    </li>
    <li>
    20. Стартовый узел отобразиться в строке «Начало»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image037.png'>
    </li>
    <li>
    21. Для того, чтобы узел увидели Операторы/ Менеджеры по продажам в строке «Статус» выберите «Опубликован» и нажмите кнопку «Сохранить».<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image039.png'>
    </li>
    <li>
    22. Для того, чтобы посмотреть скрипт в работе нажмите кнопку «Пробный звонок». В открывшемся окне нажмите на кнопку «Снять трубку»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image041.png'>
    </li>
    <li>
    23. Вы увидите скрипт в том виде, в котором с ним будут работать операторы/ менеджеры по продажам. Нажимайте на варианты ответов клиента и переходите к следующим фразам. В конце нажмите «Завершение звонка»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image043.png'>

    Отобразится окно «Звонок завершен»

    <img class='img-responsive' src='/main_page_instruction/img/big/image045.png'>
    </li>
    24. Зайдите в раздел меню «Скрипты», выпадающий раздел «Редактирование» и у Вас откроется список скриптов. <br/>
    Для просмотра скрипта нажмите кнопку с изображением телефонной трубки.<br/>
    Для продолжения редактирования скрипта нажмите кнопку с изображением карандаша.<br/>
    Для сохранения скрипта в формате SCRD нажмите на кнопку с изображением дискеты.<br/>
    Для удаления скрипта нажмите кнопку с изображеним мусорной корзины. <br/>

    <img class='img-responsive' src='/main_page_instruction/img/big/image047.png'>
    </li>
</ul>

<br/><br/><br/><br/>
<strong>Вариант 2. Создать свой скрипт с нуля.</strong>

<ul>
    <li>
    1.Зайдите в раздел меню «Скрипты» и выпадающий раздел «Редактирование». <br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image001.png'>
    </li>
    <li>
    2. Нажмите на кнопку «Создать скрипт» вверхнем левом углу.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image003.png'>
    </li>
    <li>
    3. Откроется пустрое окна скрипта. <br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image050.png'>
    </li>
    <li>
    4. Каждый скрипт состоит из узлов, которые соединены друг с другом связями. В узел входит фраза, которую произносит менеджер (верхняя синяя часть узла) и варианты ответов клиента (зеленые строки под фразой менеджера). Каждый вариант ответа клиента связан с фразой менеджера (другим узлом).
    </li>
    <li>
    5. Для создания нового узла захватите мышью кнопку «Добавить узел» и потяните вправо.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image052.png'>
    </li>
    <li>
    6. Откроется окно узла, в котором необходимо написать цель звонка и первую фразу менеджера по продажам. <br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image054.png'>
    </li>
    <li>
    7. Выберите этап разговора из списка<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image056.png'>
    </li>
    <li>
    8. Выбранный этап отобразиться в строке и нажмите «ОК»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image058.png'>
    </li>
    <li>
    9. Создан узел с фразой менеджера<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image060.png'>
    </li>
    <li>
    10. Нажмите строку «+Добавить ответ»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image062.png'>
    </li>
    <li>
    11. Впишите ответ клиента в открывшемся окне и нажмите «ОК»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image064.png'>
    </li>
    <li>
    12. Ответ появиться под узлом.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image066.png'>
    </li>
    <li>
    13. Создайте новый узел скрипта, захватив и потянув кнопку «Добавить узел» направо.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image068.png'>
    </li>
    <li>
    14. Впишите фразу менеджера и нажмите «ОК»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image070.png'>
    </li>
    <li>
    15. Для того, чтобы соединить его с вариантом ответа клиента в другом узле, нажмите на центр строки с ответом клиента и потяните красную линию к нужному узлу.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image072.png'>
    </li>
    <li>
    16. Подтяните красную линию к верхней части узла до момента, как синяя часть станет зеленой. Это значит, что связь между узлами установлена.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image074.png'>
    </li>
    <li>
    17. Впишите название скрипта слева в строку «Название»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image076.png'>
    </li>
    <li>
    18.  Выберите узел, с которого будет начиться скрипт из списка в строке «Начало» и нажмите сохранить.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image078.png'>
    </li>
    <li>
    19. Выберите статус «Опубликован», чтобы операторы/ менедежеры смогли работать со скриптов. Скрипт со статусом «Черновик» операторы/ менедежеры не увидят<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image080.png'>
    </li>
    <li>
    20. Для того, чтобы посмотреть скрипт в работе нажмите кнопку «Пробный звонок». В открывшемся окне нажмите на кнопку «Снять трубку»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image082.png'>
    </li>
    <li>
    21. Вы увидите скрипт в том виде, в котором с ним будут работать операторы/ менеджеры по продажам. Нажимайте на варианты ответов клиента и переходите к следующим фразам. В конце нажмите «Завершение звонка»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image084.png'>
    </li>
    <li>
    22. Отобразится окно «Звонок завершен»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image086.png'>
    </li>
    <li>
    22. Отобразится окно «Звонок завершен»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image045.png'>
    </li>
    </li>
    23. Зайдите в раздел меню «Скрипты», выпадающий раздел «Редактирование» и у Вас откроется список скриптов. <br/>
    Для просмотра скрипта нажмите кнопку с изображением телефонной трубки.<br/>
    Для продолжения редактирования скрипта нажмите кнопку с изображением карандаша.<br/>
    Для сохранения скрипта в формате SCRD нажмите на кнопку с изображением дискеты.<br/>
    Для удаления скрипта нажмите кнопку с изображеним мусорной корзины. <br/>

    <img class='img-responsive' src='/main_page_instruction/img/big/image047.png'>
    </li>
</ul>"
                ],
                [
                    'heading' => 'Как добавить оператора (менеджера по продажам)',
                    'content' => "
<ul>
    <li>
    1. Зайдите в раздел меню «Настройки» и выпадающий раздел «Операторы» и нажмите кнопку «Добавить опетатора»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image086.png'>
    </li>
    <li>
    2. В открывшемся окне будет указан логин и пароль для входа оператора/ менеджера.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image088.png'>
    </li>
    <li>
    3. В списке оператоторов появится оператор.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image090.png'>
    </li>
    <li>
    4.  Для корректировки пароля кликните на кнопку с изображением карандаша<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image092.png'>
    </li>
    <li>
    5. В открывшемся окне впишите новый пароль и нажмите «Сохранить»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image094.png'>
    </li>
</ul>"
                ],
                [
                    'heading' => 'Как звонить по скрипту ',
                    'content' => "
<ul>
    <li>
    1. Для звонков по скрипту передайте оператору/ менеджеру логин (выглядит так op1285@scriptdesigner.ru) и пароль
    </li>
    <li>
    2. Оператору/ менеджеру на главной странице в разделе «Вход» нужно ввести логин и пароль<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image096.png'>
    </li>
    <li>
    3. Оператор/ менеджер увидит список опубликованных скриптов (скрипты со статусом «черновик» не отображаются). Для начала работы со скрптом нужно нажать на кнопку с изображением телефонной трубки.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image098.png'>
    </li>
    <li>
    4. Скрипт начинается со стартового окна, в котором нужно нажать кнопку «Сняли трубку»<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image100.png'>
    </li>
    <li>
    5. Менеджер читает фразы скрипта, выбирает ответы клиентов, нажимает кнопку с нужным ответом и переходит к следующему узлу.  После завершения звонка или отсутствия ответа клиента в скрипте менеджер нажимает кнопку «Завершить звонок».<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image102.png'>
    </li>
    <li>
    6.  В открывшемся окне нужно выбрать вариант в строке «Цель достигнута». Данная информация используется в отчетах по скриптам и звонкам.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image104.png'>
    </li>
    <li>
    7. Выбрать вариант «Звонок по скрипту/ нет» и внести комментарии. Например, вариант ответа, которого не было в скрипте.  Данная информация используется в отчетах по скриптам и звонкам.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image106.png'>
    </li>
    <li>
    8. В системе отобразиться, что звонок Завершен. Далее нажать на кнопку с изображением телефонной трубки и начать новый звонок.<br/>
    <img class='img-responsive' src='/main_page_instruction/img/big/image108.png'>
    </li>
</ul>"
                ],
                [
                    'heading' => 'Как оценить эффективность работы по скрипту',
                    'content' => "
<ul>
    <li>
    1. Зайти в меню «Отчеты» и выбрать выпадающие меню «По скриптам» и «По звонкам»<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image110.png'>
    </li>
    <li>
    2. В подменю «По скриптам» вы увидите данные по итогам работы со скриптами.<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image112.png'>
    </li>
    <li>
    3. Вы можете отфильтровать данные по оператору, скрипту, достижению цели, периоду, нажав на кнопку «Поиск». Также Вы можете выгрузить данные в эксель. Показателем эффективности скрипта будет количество звонков, в которых цель достигнута.<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image114.png'>
    </li>
    <li>
    4. Кликнув на любую цифру в отчет по скриптам Вы попадете в перечень звонков, которые соответствует данной характеристике (например, Скрипт сломался). Эта информация нужна для внесения корректив в разработанный скрипт.<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image116.png'>
    </li>
    <li>
    5. В отчете по звонкам Вы можете найти звонки по определенным признакам: оператору, скрипту, цели, периоду и также выгрузить в эксель.<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image118.png'>
    </li>
</ul>"
                ],
                [
                    'heading' => 'Как оплатить сервис',
                    'content' => "
<ul>
    <li>
    1. Нажмите на зеленую кнопку с надписью «Пробный период до:»<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image120.png'>
    </li>
    <li>
    2. Для оплаты банковской картой нажмите на кнопку «Пополнить»<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image122.png'>
    </li>
    <li>
    3. Введите в окне сумму и нажмите «ОК»<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image124.png'>
    </li>
    <li>
    4. На странице оплаты введите реквизиты карты и произведите оплату в соответствии с инструкцией платежной системы.<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image126.png'>
    </li>
    <li>
    5. Для оплаты по выставленному счету нажмите кнопку «Редактировать реквизиты»<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image128.png'>
    </li>
    <li>
    6. В открывшемся окне внесите реквизиты юридического лица, на которое будет выставлен счет<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image130.png'>
    </li>
    <li>
    7. После внесения реквизитов название компании появиться в строке «Ваши реквизиты»<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image132.png'>
    </li>
    <li>
    8. Для выставления счета нажмие кнопку «Пополнить», выбирите вариант «Выставить счет» и впишите сумму (не менее 3000 рублей).<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image134.png'>
    </li>
    <li>
    9. После поступления оплаты в разделе «Ваш текущий тариф» нажмите кнопку «Поменять» и смените тарифный план с БЕСПЛАТНОГО на тот, который Вам подходит.<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image136.png'>
    </li>
    <li>
    10. После смены тарифного плана он будет отображен в разделе «Ваш текущий тариф»<br/>
        <img class='img-responsive' src='/main_page_instruction/img/big/image138.png'>
    </li>
</ul>"
                ],
                [
                    'heading' => 'Где взять готовые скрипты ',
                    'content' => "Скачайте шаблоны скриптов с расширением .SCRD здесь b2bbasis.info/script (в статьях есть ссылки на файлы скриптов) или в библиотеке скриптов продаж  <a href='https://www.facebook.com/groups/ScriptDesigner' target='_blank'>https://www.facebook.com/groups/ScriptDesigner</a>."
                ],
                [
                    'heading' => 'Где научиться созданию скриптов',
                    'content' => "Научиться самостоятельному созданию скриптом можно с помощью тренинга 18 скриптов продаж."
                ],
                [
                    'heading' => 'Где заказать создание скриптов "под ключ"',
                    'content' => "Заказать можно здесь <a href='http://gorstka.ru/konsultirovanie/usluga-razrabotka-skripta-zvonka' target='_blank'>gorstka.ru/konsultirovanie/usluga-razrabotka-skripta-zvonka</a> или у других экспертов"
                ]

            ];


            ?>


            <div class="panel-group main-page-instruction" id="accordion" role="tablist" aria-multiselectable="true">
                <?php
                for ($i = 0, $c = count($instruction_data); $i < $c; $i++) {
                    echo '
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="question_heading_' . $i . '">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" href="#question_' . $i . '" aria-controls="question_' . $i . '">
                                ' . $instruction_data[$i]['heading'] . '
                            </a>
                        </h4>
                    </div>
                    <div id="question_' . $i . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="question_heading_' . $i . '">
                        <div class="panel-body">' . $instruction_data[$i]['content'] . '
                        </div>
                    </div>
                </div>';
                }
                ?>
            </div>


            Если Вы не нашли ответа на свой вопрос? - напишите или позвоните нам: <br/>
            ScriptDesigner@B2Bbasis.ru<br/>
            <?php echo Yii::$app->params['phone'] ?>
        </div>
    </div>
</div>