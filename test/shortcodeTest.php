<?php

use PHPUnit\Framework\TestCase;

class shoertcodeTest extends myUnit {

	public function __construct() {
		//require_once __DIR__ . '/c.php';
		require_once 'shortcode.php';
		$this->a = new Advertikon\Shortcode();
	}

	public function test() {
		$this->clean();

		// Shortcode::do_shortcode()
		$res = $this->a->do_shortcode( '{store_name}{ip}{customer_full_name}{customer_first_name}{customer_last_name}{customer_email}{account_login_url}{store_url}{order_id}' );

		$this->assertTrue(
			is_string( $res ) && strpos( $res, '{' ) === false,
			'Failed to assert that shortcodes evaluated completely:' . $res 
		);

		$this->assertEquals( '{foo}', $this->a->do_shortcode( '{foo}' ) );

		// Shortcode::fix_content()
		$this->assertEquals( $this->a->fix_content( '<a href="http://http://foo.com">Text</a>'), '<a href="http://foo.com">Text</a>' );
		$this->assertEquals( $this->a->fix_content( '<a href="https://https://foo.com">Text</a>'), '<a href="https://foo.com">Text</a>' );
		$this->assertEquals( $this->a->fix_content( '<a href="#">Text</a>'), '<a href="#">Text</a>' );
		$this->assertEquals( $this->a->fix_content( '<a href=" ">Text</a>'), '' );
		$this->assertEquals( $this->a->fix_content( '<a href="">Text</a>'), '' );
		$this->assertEquals(
			'<a data-baz="bee" href="http://foo.com" data-id="bar" >Second</a>',
			$this->a->fix_content( '<a data-baz="bee" href=" <a data-foo="bar" href="http://foo.com" data-bar="foo" >Text</a> " data-id="bar" >Second</a>' )
		);

		// Shortcode::parse_conditional_string()
		$this->assertTrue( $this->a->parse_conditional_string( '{if_a}a{if_b}b{/if_b}{/if_a}' ) );

		// Shortcode::brace_shortcode_name()
		$this->assertEquals( '{foo}', $this->a->brace_shortcode_name( 'foo' ) );

		// Shortcode::get_shortcode_data()
		$this->assertNull( $this->a->get_shortcode_data( 'foo' ) );
		$this->assertTrue( is_array( $this->a->get_shortcode_data() ), 'Failed to retrieve shortcodes list' );

		// Shortcode::list_of_supported()
		$this->assertTrue( is_array( $this->a->list_of_supported() ) );

		$this->clean_end();

		// Shortcode::parse_conditional_string()
		$this->assertFalse( $this->a->parse_conditional_string( '{if_a}a{if_b}b{/if_b}' ) );
		$this->assertFalse( $this->a->parse_conditional_string( '{if_a}a{if_b}b{/if_a}{/if_b}' ) );
	}
}
