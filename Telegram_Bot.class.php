<?php

$db_host = 'localhost';

$db_user = 'root';

$db_password = 'vfhbyjxrf123';

$db_name = 'YTranslate';


try {

 $db = new mysqli($db_host, $db_user,$db_password, $db_name);

 $db->query("SET NAMES utf8 COLLATE utf8_unicode_ci");

} catch (Exception $e) {

 echo "Can`t connect to mysql server";

}

/**
 * Telegram Bot API Class
 */
class TBot
{

  // Токен, необходимый для подальшей работы с ботом.

  private $API_TOKEN;

  // Инициализация необходимый параметров

  function __construct($API_TOKEN)
  {
    $this->API_TOKEN = $API_TOKEN;
  }

  //Функция отправки сообщения пользователю

  public function send($user_id, $text)
  {

    $query_URL = 'https://api.telegram.org/bot'.$this->API_TOKEN.'/sendmessage?chat_id='.$user_id."&text=".$text;

    try {

      file_get_contents($query_URL);

    } catch (Exception $e) {

      exit();

    }

  }

  public function send_photo($user_id, $path)
  {

    $url  = 'https://api.telegram.org/bot'.$this->API_TOKEN."/sendPhoto?chat_id=".$user_id;

    $post_fields = array('chat_id'   => $user_id,
    'caption' => 'Добро пожаловать',
          'photo'     => new CURLFile(realpath('$path'))
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type:multipart/form-data"
    ));

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

    $output = curl_exec($ch);

  }

  public function user($id, $f_name, $l_name, $login)
  {
    global $db;

    $user = $db->query("SELECT * FROM `user` WHERE `user_id` = '$id'");

    $user = $user->fetch_assoc();

    if (empty($user['user_id'])) {

      $db->query("INSERT INTO `user`(`user_id`, `first_name`, `last_name`, `username`) VALUES('$id', '$f_name', '$l_name', '$login')");

    }else {

      return 'user_already_exist';

    }

  }

    public function get_t_direction($id)
    {

      global $db;

      $tdir = $db->query("SELECT * FROM `user` WHERE `user_id` = '$id'");

      $tdir = $tdir->fetch_assoc();

      if (empty($tdir['t_direction'])) {
        return 'empty';
      }else {
        return $tdir['t_direction'];
      }

    }

    public function set_t_direction($user, $direction)
    {

      global $db;

      $db->query("UPDATE `user` SET `t_direction` = '$direction' WHERE `user_id` = '$user'");
    }



}


?>
