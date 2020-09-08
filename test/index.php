<?php

use LightFCM\LightFcm;

require_once '../src/LightFcm.php';

class Test
{

	public function send()
	{
		// create the client
		 $light = new LightFcm("AAAA5-dUwP0:APA91bHKlvae9pp1icUe6ApIQwY4-tsHVjnAPE1umBWq3yEbcOPzCP64cZdrXGnPW37m5Gc0ye54NwNWOykCiWFN3cTzX0Z2g0Wo9-ECSt0aVjQj_03O5fXn9JVz4rxFkL98ytwusvBR");

		 $message = [
		 	"title"   => "title notif",
		 	"body"    => "content body notif",
		 	"type"    => "type",
		 	"id"      => 1,
		 ];

		// register send tokens
		$tokens = [
			"fNw6Dfe1S6KZJzCDp8Vgmo:APA91bGIFz6uC26GsaL_5DHEJmuAvw5igm0E8Tz2lTmWT5LrTKMBrYmUgO709k4uRYMxa1LY6tqCgAxAwG8eZDJRKrJC-pgGYsnp88s_HYu87xQR2cH8Bcg4ZxUdIQiMLOoIlLJ1I6hN",
		];
		// and send
		return $light->sendFcm($message, $tokens);
	}

}


$test = new Test();

$send = $test->send();

echo '<pre>' . print_r($send, true) . '</pre>';
