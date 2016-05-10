# YTranslate
Telegram translate bot(Yandex API)

Настройка вашего бота-переводчика

В файле Translate_Bot.php

<?php 

$TBot = new TBot('Токен для доступа к телеграм боту');

$YTranslate = new YTranslate('Токен яндекс-переводчика', $direction);

?>

Загрузите файлы на сервер и установите WebHook. 

Всё. Ваш бот готов)
