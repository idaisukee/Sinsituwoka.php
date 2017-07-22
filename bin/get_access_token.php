<?php
	if (file_exists('../vendor/autoload.php')) {
		require '../vendor/autoload.php';
	} else {
		require '../../../autoload.php';
	}

	use GuzzleHttp\Client;
	use Sinsituwoka\Sinsituwoka;

	$a = new Sinsituwoka();
	echo $a->accessTokenFromRemote();


