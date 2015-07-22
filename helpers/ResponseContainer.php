<?php

namespace app\helpers;

class ResponseContainer
{
	public $data;
	public $extra;

	public function __construct($status = 200, $message = 'OK', $data = null, $code = null, $extra = [])
	{
		$this->status = $status;
		$this->message = $message;
		$this->data = $data;
		$this->code = $code;
		$this->extra = (object) $extra;
	}
}
