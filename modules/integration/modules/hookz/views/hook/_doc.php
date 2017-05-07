<?php
use app\modules\integration\modules\hookz\components\WebHookPerformer;
use app\modules\integration\modules\hookz\components\HookEvent;

$markers = WebHookPerformer::getMarkers();
?>
<p>
    <strong>Наш WebHook</strong> – это POST запрос на сторонний сервер при наступлении определенного события.
</p>
<p>
    Включить или выключить WebHooks можно, используя галочку &laquo;Включить WebHooks&raquo; вверху этой страницы.
</p>
<p>
    Вы можете настроить URL - куда нужно отправлять запросы и POST данные в формате JSON, которые нужно будет отправлять.
</p>
<p>
    В WebHook вы задаете POST данные как валидный JSON - к вам на сервер они придут как обычный POST массив.
</p>
<p>
    В URL и POST данные можно встроить маркеры, которые будут заменены на данные звонка.
</p>
<p>
    Если вы вставляете маркер в POST данные - берите его в "двойные кавычки" (так как форма примет от вас только валидный JSON)
</p>

<br/>
<div class="row">
    <div class="col-xs-6">
        <legend>Доступные события</legend>
        <table class="table table-striped">
            <?php foreach (HookEvent::descriptions() as $event => $description): ?>
                <tr>
                    <td><strong><?= $description['name'] ?></strong></td>
                    <td><?= $description['description'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="col-xs-6">
        <legend>Доступные маркеры</legend>
        <table class="table table-striped">
            <?php foreach (WebHookPerformer::getMarkers() as $marker => $data): ?>
            <tr>
                <td><strong><?= $marker ?></strong></td>
                <td><?= $data['description'] . (!$data['get'] ? " <small>(только для POST данных)</small>" : null) ?></td>
                <?php endforeach; ?>
        </table>
    </div>
</div>

<br/>
<legend>Как передать свои данные в звонок</legend>
<p>При вызове прогона скрипта в конец hash-навигации добавьте: <strong>/data/base64encodedString</strong></p>
<p>Например навигация при вызове обычного прогона может выглядеть так:</p>
<pre> #/call/9999</pre>
<p>
    А вот так с переданными данными:
</p>
<pre> #/call/9999/data/base64encodedString</pre>
<p>
    <strong>base64encodedString</strong> - это любая строка или число (что бы вы не передали оно будет отправлено вам через WebHook в неизменном виде)
</p>
<p>
    Удобно base64-кодировать JSON и передавать в hash-навигацию, но можно просто передать цифру например так:
</p>
<pre> #/call/9999/data/12456</pre>
<p>
    Или обычную строку:
</p>
<pre> #/call/9999/data/мои+данные+обычной+строкой</pre>

<p>
    Вы можете, например, вызвать новую вкладку в браузере с URL:
</p>
<pre>https://scriptdesigner.ru/operator-dashboard#/call/9999/data/base64encodedString</pre>
<p>
    После загрузки страницы откроется прогонщик скрипта с загруженными в него данными (base64encodedString),
    эти данные сохранятся на сервере и прикрепятся к звонку.
    Потом эти данные можно будет получить через WebHook (маркер <strong>_data_</strong>)
</p>


<br/>
<legend>Как передать поля в звонок</legend>
<p>Аналогично как с передачей произвольных данных:</p>
<p>При вызове прогона скрипта в конец hash-навигации добавьте: <strong>/fields/base64encodedString</strong></p>
<p>
    <strong>base64encodedString</strong> - это base64-кодированный JSON следующей структуры:
</p>

<pre>
{
  "id": "1234", // Идентификатор сущности в вашей системе, к которой привязаны поля
  "type": "customer", // Тип сущности в вашей системе, к которой привязаны поля
  "fields": { // Список полей
    "phone": {
      "code": "phone",  // Код поля - должен быть уникален в наборе полей
      "type": "string", // Тип поля. Доступны типы: string, number, boolean, in, date, time
      "name": "Телефон", // Название поля
      "value": "+79131234567" // Значение (может быть пустой строкой)
    },
    "fio": {
      "code": "fio",
      "type": "string",
      "name": "ФИО",
      "value": "Иванов Иван Иванович"
    },
    "age": {
      "code": "age",
      "type": "number",
      "name": "Возраст",
      "value": "" // Значение поля можно не передавать (все поля могут быть с пустыми значениями)
    },
    "gender": {
      "code": "gender",
      "type": "in",
      "type_data": "1:Мужской,2:Женский", // Элементы списка, разделенные запятой. Могут состоять из пар ключ:значение
      "name": "Пол",
      "value": 1
    },
    "favorite_color": {
      "code": "favorite_color",
      "type": "in",
      "type_data": "Синий,Зеленый,Белый,Черный", // Элементы списка, разделенные запятой. В этом случае ключи будут равны значениям.
      "name": "Любимый цвет",
      "value": "Синий"
    }
  }
}
</pre>

<p>Обратно в WebHook вам придут поля в более сжатом формате:</p>
<pre>
{
  "id": "1234",
  "type": "customer",
  "fields": {
    "phone": "+79131234567",
    "fio": "Иванов Иван Иванович",
    "age": "25",
    "gender": "1",
    "favorite_color": "Белый"
  }
}
</pre>


<br/>
<legend>PHP скрипт, которым можно протестировать WebHook на вашем сервере</legend>
<pre>
$log_file = 'hook.log';

if (!file_exists($log_file))
    file_put_contents($log_file, "");

$record = date('Y-m-d H:i:s') . ' ' . $_SERVER['REQUEST_URI'] . PHP_EOL . json_encode($_POST);

file_put_contents($log_file, file_get_contents($log_file) . $record . PHP_EOL);

echo $record;
</pre>
<br/>
<br/>
<br/>
<br/>