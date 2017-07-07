<?php

use PHPUnit\Framework\TestCase;

class optionTest extends myUnit {

	public function __construct() {
		//require_once __DIR__ . '/c.php';
		require_once 'option.php';
		$this->a = new Advertikon\Option();
	}

	public function test() {
		$this->clean();

		$this->assertTrue( is_array( $this->a->yes_no() ) );
		$this->assertTrue( is_array( $this->a->currency() ) );
		$this->assertTrue( is_array( $this->a->currency_code() ) );
		$this->assertTrue( is_array( $this->a->shipping_methods() ) );
		$this->assertTrue( is_array( $this->a->totals() ) );
		$this->assertTrue( is_array( $this->a->shortcode() ) );
		$this->assertTrue( is_array( $this->a->geo_zone() ) );
		$this->assertTrue( is_array( $this->a->store() ) );
		$this->assertTrue( is_array( $this->a->customer_group() ) );
		$this->assertTrue( is_array( $this->a->order_status() ) );
		$this->assertTrue( is_array( $this->a->log_verbosity() ) );

		$this->clean_end();
	}
}
