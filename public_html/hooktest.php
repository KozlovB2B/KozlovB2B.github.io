<?php
$log_file = '../runtime/hook.log';

if (!file_exists($log_file))
    file_put_contents($log_file, "");

$record =   date('Y-m-d H:i:s') . ' ' .
    $_SERVER['REQUEST_URI'] . PHP_EOL .
    json_encode($_POST);

$result = file_get_contents($log_file) . $record . PHP_EOL;

file_put_contents($log_file, $result);

echo $record;
