<?php
//require_once __DIR__ . '/c.php';
require_once 'url.php';

use PHPUnit\Framework\TestCase;

class UrlTest extends myUnit {

	public function test() {

		$this->clean();

		$url = new Advertikon\Url( 'https://test.server.ua:8080/one/two/three.html?q1=1&q2=2#part' );

		$this->assertEquals( $url->get_scheme(), 'https' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '8080' );
		$this->assertEquals( $url->get_path(), '/one/two/three.html' );
		$this->assertEquals( $url->query_to_string(), 'q1=1&q2=2' );
		$this->assertEquals( $url->get_fragment(), 'part' );

		$url->parse( '//test.server.ua:8080/one/two/three.html?q1=1&q2=2#part' );

		$this->assertEquals( $url->get_scheme(), '' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '8080' );
		$this->assertEquals( $url->get_path(), '/one/two/three.html' );
		$this->assertEquals( $url->query_to_string(), 'q1=1&q2=2' );
		$this->assertEquals( $url->get_fragment(), 'part' );

		$url->parse( 'http://test.server.ua/one/two/three.html?q1=1&q2=2#part' );

		$this->assertEquals( $url->get_scheme(), 'http' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '' );
		$this->assertEquals( $url->get_path(), '/one/two/three.html' );
		$this->assertEquals( $url->query_to_string(), 'q1=1&q2=2' );
		$this->assertEquals( $url->get_fragment(), 'part' );

		$url->parse( 'http://test.server.ua:8080/one/two/three.html?q1=1&q2=2' );

		$this->assertEquals( $url->get_scheme(), 'http' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '8080' );
		$this->assertEquals( $url->get_path(), '/one/two/three.html' );
		$this->assertEquals( $url->query_to_string(), 'q1=1&q2=2' );
		$this->assertEquals( $url->get_fragment(), '' );

		$url->parse( 'http://test.server.ua:8080/one/two/three.html#part' );

		$this->assertEquals( $url->get_scheme(), 'http' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '8080' );
		$this->assertEquals( $url->get_path(), '/one/two/three.html' );
		$this->assertEquals( $url->query_to_string(), '' );
		$this->assertEquals( $url->get_fragment(), 'part' );

		$url->parse( 'http://test.server.ua:8080?q1=1&q2=2#part' );

		$this->assertEquals( $url->get_scheme(), 'http' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '8080' );
		$this->assertEquals( $url->get_path(), '/' );
		$this->assertEquals( $url->query_to_string(), 'q1=1&q2=2' );
		$this->assertEquals( $url->get_fragment(), 'part' );

		$url->parse( 'test.server.ua#part' );

		$this->assertEquals( $url->get_scheme(), '' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '' );
		$this->assertEquals( $url->get_path(), '/' );
		$this->assertEquals( $url->query_to_string(), '' );
		$this->assertEquals( $url->get_fragment(), 'part' );

		$url->parse( 'http://test.server.ua/?q1=1&q2=2' );

		$this->assertEquals( $url->get_scheme(), 'http' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '' );
		$this->assertEquals( $url->get_path(), '/' );
		$this->assertEquals( $url->query_to_string(), 'q1=1&q2=2' );
		$this->assertEquals( $url->get_fragment(), '' );

		$url->parse( 'test.server.ua/one/two/three.html#part' );

		$this->assertEquals( $url->get_scheme(), '' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '' );
		$this->assertEquals( $url->get_path(), '/one/two/three.html' );
		$this->assertEquals( $url->query_to_string(), '' );
		$this->assertEquals( $url->get_fragment(), 'part' );

		$url->parse( 'test.server.ua' );

		$this->assertEquals( $url->get_scheme(), '' );
		$this->assertEquals( $url->get_host(), 'test.server.ua' );
		$this->assertEquals( $url->get_port(), '' );
		$this->assertEquals( $url->get_path(), '/' );
		$this->assertEquals( $url->query_to_string(), '' );
		$this->assertEquals( $url->get_fragment(), '' );

		$url = new Advertikon\Url( 'https://test.server.ua:8080/one/two/three.html?q1=1&q2=2#part' );
		$this->assertEquals( $url->to_string(), 'https://test.server.ua:8080/one/two/three.html?q1=1&q2=2#part' );

		$url = new Advertikon\Url( '/one/two/three.html?q1=1&q2=2#part' );
		$this->assertEquals( $url->to_string(), '//oc2102.ua/one/two/three.html?q1=1&q2=2#part' );
		$this->assertEquals( $url->query_to_string(), 'q1=1&q2=2' );
		$this->assertEquals( $url->add_query( 'a', '4' )->query_to_string(), 'q1=1&q2=2&a=4' );

		$this->assertEquals(
			$url::url( 'payment/test_extension', array( 'a' => 4, 'b' => '5' ) ),
			'http://oc2102.ua/index.php?route=payment/test_extension&amp;a=4&amp;b=5'
		);

		$url::$spoof_23 = true;

		$this->assertEquals(
			$url::url( 'payment/test_extension', array( 'a' => 4, 'b' => '5' ) ),
			'http://oc2102.ua/index.php?route=extension/payment/test_extension&amp;a=4&amp;b=5'
		);

		$this->assertEquals(
			'http://oc2102.ua/index.php?route=extension/payment/test&amp;a=4&amp;b=5',
			$url::url( 'payment/test', array( 'a' => 4, 'b' => '5' ) )
		);

		$url::$spoof_23 = false;

		ADK()->session->data['token'] = 'foo';

		$this->assertEquals(
			'http://oc2102.ua/index.php?route=payment/test&amp;a=4&amp;b=5&amp;token=foo',
			$url::url( 'payment/test', array( 'a' => 4, 'b' => '5' ) )
		);

		$this->assertEquals( $url::catalog_url(), '//oc2102.ua/' );
		$this->assertEquals( $url::catalog_url( 'auto' ), 'http://oc2102.ua/' );
		$this->assertEquals( $url::catalog_url( true ), 'http://oc2102.ua/' );
		$this->assertEquals( $url::catalog_url( false ), 'http://oc2102.ua/' );

		$this->assertEquals( $url::admin_url(), '' );
		$this->assertEquals( $url::admin_url( 'auto' ), '' );
		$this->assertEquals( $url::admin_url( true ), '' );
		$this->assertEquals( $url::admin_url( false ), '' );

		$this->clean_end();
	}
}
