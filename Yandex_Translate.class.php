<?php

/**
 * Класс для работы с API перевода Яндекс
 */
class YTranslate
{

/**
  * API_KEY Переменная хранящая ваш ключ для доступа к API Яндекс переводчика
  */

  private $API_KEY;

/**
  * translation_direction - Направление перевода. К примеру ru-uk (с Русского на Укринаский)
  */

  private $t_direction;

  /**
    * symbolLimit - максимальное число символов для отправки переводчику
    */
  public static $symbolLimit = 2000;

  /**
    * sentensesDelimiter- символы, по которым текст делится на предложения
    */
  public static $sentensesDelimiter = '.';

  /**
    * Инициализируем необходимые нам параметры
    */

  function __construct($API_KEY, $t_direction)
  {

    $this->API_KEY = $API_KEY;

    $this->t_direction = $t_direction;

  }

  public function translate($text)
  {

    /**
      * Формирование строки для запроса к сереверу перевода Яндекса
      */

    $translation_url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=".$this->API_KEY."&text=".$text."&lang=".$this->t_direction;

    $translation_response = file_get_contents($translation_url);

    /**
      * После выполнения запроса - обрабатываем JSON ответ от сервера и преобразуем его в массив данных
      */

    $translation_response = json_decode($translation_response, true);

    /**
      * Проверяем на наличие ошибок в ответе от сервера перевода
      * И если таковых нету - возвращаем переведенный текст
      */

    switch ($translation_response['code'] ) {
      case '401':
        return 'Неправильный API-ключ';
        break;
      case '402':
        return 'API-ключ заблокирован';
        break;
      case '404':
        return 'Превышено суточное ограничение на объем переведенного текста';
      break;
      case '413':
          return 'Превышен максимально допустимый размер текста';
      break;
      case '422':
          return 'Текст не может быть переведен';
      break;
      case '501':
          return 'Заданное направление перевода не поддерживается';
      break;

      default:
        return $translation_response['text'][0];
        break;
    }
  }

  protected static function toSentenses ($text) {
       $sentArray = explode(self::$sentensesDelimiter, $text);
       return $sentArray;
   }

   /**
    * Разделение текста на массив больших кусков
    */

   public static function toBigPieces ($text) {
       $sentArray = self::toSentenses($text);
       $i = 0;
       $bigPiecesArray[0] = '';
       for ($k = 0; $k < count($sentArray); $k++) {
           $bigPiecesArray[$i] .= $sentArray[$k].self::$sentensesDelimiter;
           if (strlen($bigPiecesArray[$i]) > self::$symbolLimit){
               $i++;
               $bigPiecesArray[$i] = '';
           }
       }

       return $bigPiecesArray;
   }

   /**
     * Склеивание текста
     */
    public static function fromBigPieces (array $bigPiecesArray) {

        ksort($bigPiecesArray);

        return implode($bigPiecesArray);
    }
}


?>
