<?php
if ( ! defined( 'DIR_SYSTEM' ) ) {
	chdir( '../../../..' );
	require_once './index.php';

	echo PHP_EOL . 'CHDIR and include index.php';
}

//set_include_path( get_include_path() . ':' . __DIR__ . '/..' );
//echo get_include_path();
echo PHP_EOL . 'Common config';
echo PHP_EOL . 'CWD: ' . getcwd() . PHP_EOL;

require_once( 'advertikon.php' );
require_once( 'my_unit.php' );
//require_once( 'test_listner.php' );

