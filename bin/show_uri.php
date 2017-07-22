<?php
	if (file_exists('../vendor/autoload.php')) {
		require '../vendor/autoload.php';
	} else {
		require '../../../autoload.php';
	}

	use Sinsituwoka\Sinsituwoka;

	$s = new Sinsituwoka();

	echo $s->uri;
