<?php

namespace app\helpers;

class Phone
{
	public static function prepare($phone)
	{
		$phone = preg_replace("/[^0-9]/", "", $phone);
		$phone = "+${phone}";
		return $phone;
	}
}
