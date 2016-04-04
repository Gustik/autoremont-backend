<?php

namespace app\helpers;

use app\models\Variable;

class PushHelper
{
    public static $address = 'https://gcm-http.googleapis.com/gcm/send';
    public static $key = 'AIzaSyBR2bIRlaaSyHwDh-UmQn0-uSDbOh1mxo0';

	private static function prepare($to, $text)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::$address);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = [
            "Authorization: key={static::$key}",
            "Content-Type: application/json"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = [
            "collapse_key" => "new_order",
            "to" => $to,
            "data" => [
                "message" => [
                    "text" => $text 
                ]
            ]
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		return $ch;
	}

	public static function send($to, $text)
	{
		$ch = PushHelper::prepare($to, $text);
        $result = curl_exec($ch);
        curl_close($ch);

		return $result;
	}
}
