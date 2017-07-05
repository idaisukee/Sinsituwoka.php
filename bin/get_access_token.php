<?php

	require '../Sinsituwoka.php';

use GuzzleHttp\Client;
use Sinsituwoka\Sinsituwoka;

$a = new Sinsituwoka();
echo $a->accessTokenFromRemote();


