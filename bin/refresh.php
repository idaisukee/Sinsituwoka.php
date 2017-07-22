<?php
	if (file_exists('../vendor/autoload.php')) {
		require '../vendor/autoload.php';
	} else {
		require '../../../autoload.php';
	}
	use Sinsituwoka\Sinsituwoka;

	$s = new Sinsituwoka();

	print_r( $s->accessTokenFromLocal());
	var_dump( $s->refresh());
	var_dump( $s->bearer());
