<?php

use LightFCM\LightFcm;

require_once '../src/LightFcm.php';

class Test
{

	public function send()
	{
		// create the client
		 $light = new LightFcm("AAAA5-dUwP0:server-key*********************************");

		 $message = [
		 	"title"   => "title notif",
		 	"body"    => "content body notif",
		 	"type"    => "type",
		 	"id"      => 1,
		 ];

		// register send tokens
		$tokens = [
			"fNw6Dfe1S6KZJzCDp8Vgmo:client token *******************************************",
		];
		// and send
		return $light->sendFcm($message, $tokens);
	}

}


$test = new Test();

$send = $test->send();

echo '<pre>' . print_r($send, true) . '</pre>';
