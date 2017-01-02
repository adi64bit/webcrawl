<?php
  namespace App\Helper;

  class GlobalFunction {

    /*
    * Parse url to get the domain only
    * Example: http://wwww.google.com -> google.com
    */
    public static function parseUrl($url)
    {
      $domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
      return $domain;
    }

    /*
    * Validate Date Format
    */
    public static function validateDate($date)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

  }
?>
