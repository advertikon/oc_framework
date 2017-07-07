<?php

use PHPUnit\Framework\TestCase;

class fsTest extends myUnit {

	public function __construct() {
		//require_once __DIR__ . '/c.php';
		require_once 'fs.php';
		$this->a = new Advertikon\Fs();
	}

	public function test() {

		$this->clean();

		// Fs::plant_file()
		$this->assertEquals( rtrim( DIR_SYSTEM, '/' ), $this->a->plant_file( 'oc2102/system' ) );
		$this->assertEquals( DIR_SYSTEM . 'foo', $this->a->plant_file( 'oc2102/system/foo' ) );
		$this->assertEquals( DIR_SYSTEM . 'foo', $this->a->plant_file( 'oc2102/system/././foo/.' ) );
		$this->assertEquals( rtrim( DIR_SYSTEM, '/' ), $this->a->plant_file( 'oc2102/system/foo/../' ) );
		$this->assertEquals( dirname( DIR_SYSTEM ) . '/bar/boo', $this->a->plant_file( 'oc2102/system/../foo/../bar/boo/' ) );
		$this->assertEquals( dirname( DIR_SYSTEM ) . '/bar/boo', $this->a->plant_file( 'oc2102/system/../foo/.././bar/boo/.' ) );
		$this->assertFalse( $this->a->plant_file( 'oc2102/system/../foo/../bar/boo/../../../' ) );

		// Fs::mkfile()
		$this->assertTrue( $this->a->mkdir( __DIR__ . '/foo/bar' ) );
		file_put_contents( __DIR__ . '/foo/baz', '' );
		file_put_contents( __DIR__ . '/foo/bar/baz', '' );

		// Fs::iterate_directory()
		$count = 0;
		$this->a->iterate_directory( __DIR__ . '/foo', function() use( &$count ) { $count++; } );
		$this->assertEquals( $count, 4 );

		// Fs::rmdir()
		$this->assertEquals( $this->a->rmdir( __DIR__ . '/foo' ), null );

		// Fs::get_dir_size()
		$this->assertTrue( is_numeric( $this->a->get_dir_size( __DIR__ ) ) );

		// Fs::get_mine_icon()
		$this->assertEquals( $this->a->get_mime_icon( 'text/csv' ), 'file-code-o' );

		// Fs::above_store_root()
		$this->assertEquals( rtrim( DIR_SYSTEM, '/' ), $this->a->above_store_root( DIR_SYSTEM ) );
		$this->assertEquals( dirname( DIR_SYSTEM ), $this->a->above_store_root( dirname( DIR_SYSTEM ) ) );
		$this->assertFalse( $this->a->above_store_root( dirname( DIR_SYSTEM ) . 'foo' ) );
		$this->assertFalse( $this->a->above_store_root( dirname( dirname( DIR_SYSTEM ) ) ) );

		$this->clean_end();
	}
}
