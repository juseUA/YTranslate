<?php

  require 'Yandex_Translate.class.php';

  require 'Telegram_Bot.class.php';

  $TBot = new TBot('237005287:AAECLicmgkg1I8_lQVLR7EIsX_VKhirU-Ww');

  $content = file_get_contents("php://input");

  $update = json_decode($content, true);

  $chatID = $update["message"]["chat"]["id"];

  $need_translate = $update["message"]["text"];

  $user = $update["message"]["from"];

  $direction;

  $new_row = urlencode("\n");

  if ($need_translate == '/start') {

    $message = $user['first_name'].", здравствуйте 😇".$new_row."Мы поможем перевести вам текст!".$new_row."Для начала необходимо установить направление перевода".$new_row."/ruen : Русский - Английский".$new_row."/enru : Английский - Русский".$new_row."/rude : Русский - Немецкий".$new_row."/deru : Немецкий - Русский";

    $TBot->user($user['id'], $user['first_name'], $user['last_name'], $user['username']);

    $TBot->send($chatID, $message);

    $TBot->send_photo($chatID, 'welcome.png');

    die();

  }


  if ($need_translate == '/ruen') {

    $TBot->set_t_direction($user['id'], 'ru-en');

    $message = "Установлен перевод с Русского на Английский";

    $TBot->send($chatID, $message);

    die();

  }

  if ($need_translate == '/enru') {

    $TBot->set_t_direction($user['id'], 'en-ru');

    $message = "Установлен перевод с Английского на Русский";

    $TBot->send($chatID, $message);

    die();

  }

  if ($need_translate == '/rude') {

    $TBot->set_t_direction($user['id'], 'ru-de');

    $message = "Установлен перевод с Русского на Немецкий";

    $TBot->send($chatID, $message);

    die();

  }

  if ($need_translate == '/deru') {

    $TBot->set_t_direction($user['id'], 'de-ru');

    $message = "Установлен перевод с Немецкого на Русский";

    $TBot->send($chatID, $message);

    die();

  }

  if ($TBot->user($user['id'], $user['first_name'], $user['last_name'], $user['username']) == 'user_already_exist') {

    if ($TBot->get_t_direction($user['id']) == 'empty') {

      $message = "Для начала необходимо установить направление перевода".$new_row."/ruen : Русский - Английский".$new_row."/enru : Английский - Русский".$new_row."/rude : Русский - Немецкий".$new_row."/deru : Немецкий - Русский";

      $TBot->send($chatID, $message);

      die();

    }else {

      $direction = $TBot->get_t_direction($user['id']);

      $YTranslate = new YTranslate('trnsl.1.1.20160505T115011Z.c576880a1febe027.fa470e4f59c58f4330632e77854b8ccef86ac266', $direction);

      if (mb_strlen($need_translate) > 200) {

        $textArray = YTranslate::toBigPieces($need_translate);

        $numberOfTextItems = count($textArray);

        foreach ($textArray as $key=>$textItem){

            $translatedItem = $YTranslate->translate($textItem);

            $translatedArray[$key] = $translatedItem;

        }

        $translatedBigText = YTranslate::fromBigPieces($translatedArray);

        $TBot->send($chatID, $translatedBigText);

      }else {

        $TBot->send($chatID, $YTranslate->translate($need_translate));

      }

    }

  }else {
    if ($TBot->get_t_direction($user) == 'empty') {

      $message = "Для начала необходимо установить направление перевода".$new_row."/ruen : Русский - Английский".$new_row."/enru : Английский - Русский".$new_row."/rude : Русский - Немецкий".$new_row."/deru : Немецкий - Русский";

      $TBot->send($chatID, $message);
    }
  }

 ?>
