<?php

//require_once __DIR__ . '/c.php';
$a = ADK();

use PHPUnit\Framework\TestCase;

class AdvertikonCatalogTest extends myUnit {

	public function test() {
		global $a;

		$this->clean();

		// Advertikon::instance()
		$this->assertEquals( ADK(), $a);

		// Advertikon::config()
		$a->config->set( $a->prefix_name( 'foo' ), 'bar' );
		$this->assertEquals( $a->config( 'foo' ), 'bar' );

		$a->config->set( $a->prefix_name( 'foo' ), array( "a" => array( "b" => array( "c" => 1 ) ) ) );
		$this->assertEquals( $a->config( 'foo' ), array( "a" => array( "b" => array( "c" => 1 ) ) ) );
		$this->assertEquals( $a->config( 'foo/a/b/c' ), 1 );

		// ************************** Set extension's prefix as "test" *****************************
		// $a->code = 'test';

		// $a->config->set( 'test_foo', array( "a" => array( "b" => array( "c" => 1 ) ) ) );
		// $this->assertEquals( $a->config( 'foo' ), array( "a" => array( "b" => array( "c" => 1 ) ) ) );
		// $this->assertEquals( $a->config( 'foo/a/b/c' ), 1 );
		// $this->assertEquals( $a->config( 'test_foo/a/b/c' ), 1 );

		// Advertikon::__set() Advertikon::__get()
		$a->foo_val = 1;
		$this->assertEquals( $a->foo_val, 1 );

		// Advertikon::renderer()
		$this->assertEquals( get_class( $a->renderer() ), 'Advertikon\Renderer' );

		// Advertikon::r()
		$this->assertEquals( get_class( $a->r() ), 'Advertikon\Renderer' );

		// Advertikon::query()
		$this->assertEquals( get_class( $a->query() ), 'Advertikon\Query' );

		// Advertikon::q()
		$this->assertEquals( get_class( $a->q() ), 'Advertikon\Query' );

		// Advertikon::option()
		$this->assertEquals( get_class( $a->option() ), 'Advertikon\Option' );

		// Advertikon::o()
		$this->assertEquals( get_class( $a->o() ), 'Advertikon\Option' );

		// Advertikon::__()
		$this->assertEquals( $a->__( 'foo' ), 'foo' );
		$this->assertEquals( $a->__( 'foo %s', 'bar' ), 'foo bar' );

		// Advertikon::get_from_array()
		$this->assertEquals(
			$a->get_from_array( array( "a" => array( "b" => array( "c" => 1 ) ) ), 'a/b/c' ),
			1
		);

		$this->assertEquals(
			$a->get_from_array( array( "a" => array( "b" => array( "c" => 1 ) ) ), 'a/b' ),
			array( "c" => 1 )
			);

		$this->assertEquals(
			$a->get_from_array( array( "a" => array( "b" => array( "c" => 1 ) ) ),array( "a", "b", "c" ) ),
			1
		);

		$this->assertEquals(
			$a->get_from_array(	array( "a" => array( "b" => array( "c" => 1 ) ) ) ),
			array( "a" => array( "b" => array( "c" => 1 ) ) )
		);

		$this->assertEquals( $a->get_from_array( 1, 'a/b/c' ), null );
		$this->assertEquals( $a->get_from_array( 1 ), 1 );


		// Advertikon::p()
		$a->request->post['foo'] = 'bar';
		$this->assertEquals( $a->post( 'foo' ), 'bar' );
		$this->assertEquals( $a->p( 'fooo' ), null );
		$this->assertEquals( $a->p( 'fooo', 1 ), 1 );

		unset( $a->request->post['foo'] );

		// Advertikon::post()
		$a->request->post['foo'] = 'bar';

		$this->assertEquals( $a->post( 'foo' ), 'bar' );
		$this->assertEquals( $a->post( 'fooo' ), null );

		$a->request->post[ $a->prefix_name( 'foo' ) ] = 'test_bar';
		$this->assertEquals( $a->post( 'foo' ), 'test_bar' );

		unset( $a->request->post[ $a->prefix_name( 'foo' ) ], $a->request->post['foo'] );

		// Advertikon::get_value_from_post()
		$a->request->post['bar'] = 'bar';
		$a->config->set( $a->prefix_name( 'foo' ), 'config_bar' );
		$this->assertEquals( $a->get_value_from_post( 'foo' ), 'config_bar' );

		$a->request->post[ $a->prefix_name( 'foo' ) ] = 'test_bar';
		$this->assertEquals( $a->get_value_from_post( 'foo' ), 'test_bar' );

		$a->request->post[ $a->prefix_name( 'foo' ) ] = array( "a" => array( "b" => 1 ) );
		$this->assertEquals( $a->get_value_from_post( 'foo[a][b]' ), '1' );
		$this->assertEquals( $a->get_value_from_post( 'foo[a]' ), array( "b" => 1 ) );

		unset( $a->request->post[ $a->prefix_name( 'foo' ) ], $a->request->post['foo'] );

		$this->assertEquals( $a->get_value_from_post( 'foo'), 'config_bar' );

		// Advertikon::request()
		$a->request->request['foo'] = 'bar';
		$this->assertEquals( $a->request( 'foo' ), 'bar' );
		$this->assertEquals( $a->request( 'fooo' ), null );
		$this->assertEquals( $a->request( 'fooo', 1 ), 1 );

		// Advertikon::prefix_name()
		// $this->assertEquals( $a->prefix_name( 'foo' ), 'test_foo' );
		$this->assertEquals( $a->prefix_name( $a->prefix_name( 'foo' ) ), $a->prefix_name( 'foo' ) );

		// Advertikon::strip_prefix()
		$this->assertEquals( $a->strip_prefix( $a->prefix_name( 'foo' ) ), 'foo' );

		// Advertikon::get_prefix()
		// $this->assertEquals( $a->get_prefix(), 'test' );

		// Advertikon::object_to_array()
		$o = new \stdClass();
		$o->a = new \stdClass();
		$o->a->b = 1;

		$this->ar = array();
		$this->ar['a'] = new \stdClass();
		$this->ar['a']->b = 1;

		$this->assertEquals( $a->object_to_array( $o ), array( "a" => array( "b" => 1 ) ) );
		$this->assertEquals( $a->object_to_array( $this->ar ), array( "a" => array( "b" => 1 ) ) );
		$this->assertEquals( $a->object_to_array( 'array' ), 'array' );

		// Advertikon::fix_json_sting()
		$str = json_encode( array( "a" => 'Юникод строка', "b" => "O'Railly & sons" ), JSON_HEX_AMP | JSON_HEX_APOS );
		$str = stripcslashes( $str );
		$result = new \stdClass();
		$result->a = 'Юникод строка';
		$result->b = "O'Railly & sons";

		$this->assertEquals( json_decode( $a->fix_json_string( $str ) ), $result );

		// Advertikon::create_array_structure()
		$this->arr = array( 'foo' => 1 );
		$a->create_array_structure( $this->arr, 'foo/bar/bazz' );
		$this->assertEquals( $this->arr, array( 'foo' => array( 'bar' => array( 'bazz' => array() ) ) ) );

		// Advertikon::escape_name()
		$this->assertEquals( $a->escape_name( 'foo-bar' ), 'foo\\-bar' );

		// Advertikon::build_name();
		$this->assertEquals( $a->build_name( 'foo/bar/bazz' ), 'foo[bar][bazz]' );
		$this->assertEquals( $a->build_name( 'foo-bar-bazz' ), 'foo[bar][bazz]' );
		$this->assertEquals( $a->build_name( 'foo-bar/bazz' ), 'foo[bar][bazz]' );
		$this->assertEquals( $a->build_name( $a->escape_name( 'foo-bar/baz') ), 'foo-bar[baz]' );

		// Advertikon::is_ended_with()
		$this->assertTrue( $a->is_ended_with( 'foobar', 'bar') );
		$this->assertFalse( $a->is_ended_with( 'foobar', 'baz') );

		// Advertikon::get_lang_id()
		$this->assertTrue( is_numeric( $a->get_lang_id() ) );

		// Advertikon::get_img_url()
		$this->assertEquals( $a->get_img_url( DIR_IMAGE . 'foo/bar.img' ), '//oc2102.ua/image/foo/bar.img' );
		$this->assertEquals( $a->get_img_url( 'foo/bar.img' ), '' );

		// Advertikon::get_view_route()
		$this->assertEquals( $a->get_view_route( 'foo/bar' ), 'default/template/foo/bar.tpl' );

		// Advertikon::get_template()
		$this->assertEquals( $a->get_template( 'foo/bar' ), 'default/template/foo/bar.tpl' );

		// Advertikon::sort_by()
		$in = array( array( "foo" => 3), array( "foo" => 0 ), array( "foo" => 1 ) );
		$a->sort_by( $in, 'foo' );
		$this->assertEquals(
			$in,
			array( array( "foo" => 0 ), array( "foo" => 1 ), array( "foo" => 3 ) )
		);

		$in = array( array( "foo" => 3), array( "foo" => 0 ), array( "foo" => 1 ) );
		$a->sort_by( $in, 'foo' );
		$this->assertNotEquals(
			$in,
			array( array( "foo" => 3 ), array( "foo" => 0 ), array( "foo" => 1 ) )
		);

		$in = array( array( "foo" => '3' ), array( "foo" => 0 ), array( "foo" => '1' ) );
		$a->sort_by( $in, 'foo' );
		$this->assertEquals(
			$in,
			array( array( "foo" => 0 ), array( "foo" => '1' ), array( "foo" => '3' ) )
		);

		// Advertikon::get_store_url()
		$this->assertEquals( $a->get_store_url(), 'http://oc2102.ua/' );

		// Advertikon::get_store_href()
		$this->assertEquals( $a->get_store_href(), 'http://oc2102.ua/' );

		// Advertikon::get_customer()
		$a->session->data['guest'] = 'foo';
		$this->assertEquals( $a->get_customer(), 'foo' );
		unset( $a->session->data['guest'] );

		// Advertikon::get_order_products()
		$this->assertTrue( is_array( $a->get_order_products( 1 ) ) );

		// Advertikon::get_products_by_id()
		$this->assertTrue( is_array( $a->get_products_by_id( 1 ) ) );

		// Advertikon::get_order_vouchers()
		$this->assertTrue( is_array( $a->get_order_vouchers( 1 ) ) );

		// Advertikon::get_order_totals()
		$this->assertTrue( is_array( $a->get_order_totals( 1 ) ) );

		// Advertikon::get_order_status_name()
		$this->assertTrue( is_string( $a->get_order_status_name( 1 ) ) );

		//Advertikon::get_order_downloaded_products()
		$this->assertTrue( is_array( $a->get_order_downloaded_products( 1 ) ) );

		// Advertikon::get_order_info()
		$this->assertTrue( is_a( $a->get_order_info( 1 ), 'Advertikon\Db_Result' ) );

		// Advertikon::get_customer_group_info()
		$this->assertTrue( is_array( $a->get_customer_group_info( 1 ) ) );

		// Advertikon::get_product_info()
		$this->assertTrue( is_array( $a->get_product_info( 1 ) ) );

		// Advertikon::get_region_info()
		$this->assertTrue( is_array( $a->get_region_info( 1 ) ) );

		// Advertikon::get_voucher()
		$this->assertTrue( is_array( $a->get_voucher( 1 ) ) );

		// Advertikon::get_customer_by_email()
		$this->assertTrue( is_array( $a->get_customer_by_email( 'foo@bar' ) ) );

		// Advertikon::str_slice()
		$this->assertEquals( $a->str_slice( 'qwerty', 1, 3 ), 'qty' );
		$this->assertEquals( $a->str_slice( 'qwerty', 3, 1 ), 'qerty' );
		$this->assertEquals( $a->str_slice( 'qwerty', 1 ), 'q' );
		$this->assertEquals( $a->str_slice( 'qwerty', 1, -3 ), 'qty' );
		$this->assertEquals( $a->str_slice( 'qwerty', -5, 3 ), 'qty' );
		$this->assertEquals( $a->str_slice( 'qwerty', null, 3 ), 'ty' );
		$this->assertEquals( $a->str_slice( 'qwerty', -5, -3 ), 'qty' );

		// Advertikon::get_file_upload_error()
		$this->assertTrue( is_string( $a->get_file_upload_error( 1 ) ) );

		// Advertikon::format_bytes()
		$this->assertEquals( $a->format_bytes( 0 ), '0 B' );
		$this->assertEquals( $a->format_bytes( -2 ), '0 B' );
		$this->assertEquals( $a->format_bytes( 'a' ), '0 B' );
		$this->assertEquals( $a->format_bytes( '13.554' ), '13.55 B' );
		$this->assertEquals( $a->format_bytes( 12.45555 ), '12.46 B' );
		$this->assertEquals( $a->format_bytes( 12345 ), '12.35 kB' );
		$this->assertEquals( $a->format_bytes( 1234567 ), '1.23 MB' );
		$this->assertEquals( $a->format_bytes( 12345673245 ), '12.35 GB' );

		// Advertikon::json_response()
		// Just to catch run-time errors
		$this->assertEquals( $a->json_response( array() ), null );

		// Advertikon::log()
		$this->assertEquals( $a->log( 'foo', $a->log_debug_flag ), null );
		$this->assertEquals( $a->log( function(){ return 'bar'; }, $a->log_error_flag ), null );
		$this->assertEquals( $a->log( 'foo' ), null );

		// One-time cache
		$a->add_to_cache( 'foo/bar', 'bazz' );
		$this->assertTrue( $a->has_in_cache( 'foo/bar' ) );
		$this->assertFalse( $a->has_in_cache( 'foo/bar/bazz' ) );
		$this->assertEquals( $a->get_from_cache( 'foo/bar' ), 'bazz' );

		// Advertikon::underscore()
		$this->assertEquals( $a->underscore( 'FooBarBaz' ), 'foo_bar_baz' );
		$this->assertEquals( $a->underscore( 'Foo23Bar33Baz' ), 'foo23_bar33_baz' );

		// Advertikon::camelcase()
		$this->assertEquals( $a->camelcase( 'foo_bar_baz' ), 'fooBarBaz' );

		// Advertikon::has_permissions()
		$this->assertTrue( is_bool( $a->has_permissions( 'foo' ) ) );

		// Advertikon::is_empty()
		$o = new \stdClass();
		$this->assertTrue( $a->is_empty( $o ) );
		$o->foo = new \stdClass();
		$this->assertTrue( $a->is_empty( $o ) );
		$o->foo->bar = array();
		$this->assertTrue( $a->is_empty( $o ) );
		$o->foo->bar[] = false;
		$this->assertFalse( $a->is_empty( $o ) );
		$this->arr = array( "foo" => array( "bar" => array() ) );
		$this->assertTrue( $a->is_empty( $this->arr ) );
		$this->arr['foo']['barr'][] = 'bazz';
		$this->assertFalse( $a->is_empty( $this->arr ) );
		$this->assertTrue( $a->is_empty( false ) );
		$this->assertFalse( $a->is_empty( true ) );

		// Advertikon::obscure_str()
		$this->assertEquals( $a->obscure_str( 'q w e r t y', 50, '+' ),  '+ + + r t y' );
		$this->assertEquals( $a->obscure_str( 'q w e r t y', null, null, true ),  '********* y' );
		$this->assertEquals( $a->obscure_str( 4 ),  '' );

		// Advertikon::json_decode()
		$in = array( "foo" => 'bar' );
		$out = new \stdClass();
		$out->foo = 'bar';
		$this->assertEquals( $a->json_decode( json_encode( $in ) ), $out );

		// Advertikon::json_encode()
		$this->assertEquals( $a->json_encode( array( 'foo' => 'foo"bar\'bar' ) ), '{"foo":"foo\u0022bar\u0027bar"}' );

		// Advertikon::get_json_error()
		$this->assertTrue( is_string( $a->get_json_error() ) );

		// Advertikon::add_custom_field()
		$this->assertTrue( is_null( $a->add_custom_field( 1, 'foo', 'bar' ) ) );

		// Advertikon::get_custom_field()
		$this->assertEquals( $a->get_custom_field( 1 )->foo, 'bar' );

		// Advertikon::get_product()
		$this->assertTrue( is_array( $a->get_product( 1 ) ) );

		// Advertikon::get_order_model()
		$this->assertTrue( in_array( 'Model', class_parents( $a->get_order_model() ) ) );

		// Advertikon::get_recurring_info_model()
		$this->assertTrue( in_array( 'Model', class_parents( $a->get_recurring_info_model() ) ) );

		// Admin::catalog_url()
		$this->assertEquals( $a->catalog_url(), '//oc2102.ua/' );

		// Admin::admin_url()
		// Admin URL can not be reached from Catalog side
		$this->assertEquals( $a->admin_url(), '' );

		// Advertikon::load()
		$a->compression_level = Advertikon\Advertikon::COMPRESSION_LEVEL_NONE;
		$this->assertTrue( is_string( $a->load( array( 'catalog/view/javascript/advertikon/advertikon.js' ), 'script' ) ) );

		$a->compression_level = Advertikon\Advertikon::COMPRESSION_LEVEL_WHITESPACES;
		$this->assertTrue( is_string( $a->load( array( 'catalog/view/javascript/advertikon/advertikon.js' ), 'script' ) ) );

		$this->clean_end(); 
		// Advertikon::compress()
		$a->compression_cache = true;
		$this->assertTrue(
			is_string(
				$a->compress(
					$a->catalog_url() . 'catalog/view/javascript/advertikon/advertikon.js',
					'js'
				)
			)
		);

		$a->compression_cache = false;
		$this->assertTrue(
			is_string(
				$a->compress(
					$a->catalog_url() . 'catalog/view/javascript/advertikon/advertikon.js',
					'js'
				)
			)
		);

		$this->clean();

		// Advertikon::get_shipping_methods()
		$this->assertTrue( is_array( $a->get_shipping_methods() ) );

		// Advertikon::get_lang_caption()

		// Advertikon::get_languages()

		// Advertikon::caption()

		$this->clean_end();			
	}
}
