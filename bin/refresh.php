<?php
	if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
		require __DIR__ . '/../vendor/autoload.php';
	} else {
		require __DIR__ . '/../../../autoload.php';
	}
	use Sinsituwoka\Sinsituwoka;

	$s = new Sinsituwoka();

	print_r( $s->accessTokenFromLocal());
	var_dump( $s->refresh());
	var_dump( $s->bearer());
