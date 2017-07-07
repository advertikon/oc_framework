<?php

use PHPUnit\Framework\TestCase;

class taskTest extends myUnit {

	public function __construct() {
		//require_once __DIR__ . '/c.php';
		require_once 'task.php';
		$this->a = new Advertikon\Task();
	}

	public function test() {
		$this->clean();

		$this->a->install();
		$this->a->add_task( ADK()->catalog_url() . 'index.php?route=foo/bar', '* * * * *', 10 );
		$this->assertTrue( $this->a->task_exists( ADK()->catalog_url() . 'index.php?route=foo/bar', '* * * * *', 10 ) );

		ob_start();
		$this->a->run();
		ob_end_flush();
		ob_clean();

		$this->a->delete_task( ADK()->catalog_url() . 'index.php?route=foo/bar', '* * * * *', 10 );
		$this->assertFalse( $this->a->task_exists( ADK()->catalog_url() . 'index.php?route=foo/bar', '* * * * *', 10 ) );

		$this->clean_end();

	}
}
