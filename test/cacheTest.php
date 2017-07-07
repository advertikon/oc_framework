<?php

use PHPUnit\Framework\TestCase;

class cacheTest extends myUnit {

	public function __construct() {
		require_once 'cache.php';
	}

	public function test() {
		$a = new Advertikon\Cache();

		$this->clean();

		require_once 'dummy.php';
		$d = new Dummy();

		$a->set( 'test', $d );
		$this->assertTrue( is_object( $a->get( 'test' ) ) );
		$this->assertTrue( in_array( 'PHPUnit\Framework\TestCase', class_parents( $a->get( 'test' ) ) ) );
		$a->delete( 'test' );
		$this->assertNull( $a->get( 'test' ) );

		$a->set( 'test', $d, 1 );
		$this->assertTrue( is_object( $a->get( 'test' ) ) );
		$this->assertTrue( in_array( 'PHPUnit\Framework\TestCase', class_parents( $a->get( 'test' ) ) ) );
		$a->delete( 'test' );
		$this->assertNull( $a->get( 'test' ) );

		$this->clean_end();
	}
}
