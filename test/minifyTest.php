<?php
use PHPUnit\Framework\TestCase;

class minifyTest extends myUnit {

	public function __construct() {
		//require_once __DIR__ . '/c.php';
		require_once 'minify.php';
		$this->a = new Advertikon\Minify( Advertikon\Advertikon::COMPRESSION_LEVEL_WHITESPACES );
	}

	public function test() {
		$this->assertTrue( is_string( $this->a->get( 'advertikon/advertikon.js', 'js' ) ) );
	}
}
