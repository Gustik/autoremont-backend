<?php

namespace app\helpers;

use app\models\Variable;

/*
	WARNING ! ! !
	This SMS Helper may be used only with 'https://smsc.ru'
*/

class Sms
{
    public static $address = 'https://smsc.ru/sys/send.php';
    public static $login = 'keydriver';
    public static $password = 'U_DMKu8a';
    public static $charset = 'utf-8';
    public static $fmt = '3'; // Get response in JSON

	private static function prepare($to, $text, $cost = false)
	{
		$url = static::$address .
			'?login=' . static::$login .
			'&psw=' . static::$password .
			'&sender=' . Variable::getParam('sms-sender') .
			'&phones=' . $to .
			'&charset=' . static::$charset .
			'&fmt=' . static::$fmt .
			'&mes=' . urlencode($text);
		if ($cost) {
			$url += '&cost=1';
		}
		return $url;
	}

	public static function send($to, $text)
	{
		$url = static::prepare($to, $text);
		if (Variable::getParam('environment') != 'DEV') {
			$data = json_encode(file_get_contents($url));
		} else {
			$data = true;
		}
		return ( isset($data['error']) ? false : true );
	}

	public static function cost($to, $text)
	{
		$url = static::prepare($to, $text, true);
		$data = json_encode(file_get_contents($url));
		return ( isset($data['cost']) ? $data['cost'] : false );
	}
}
