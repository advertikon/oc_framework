<?php
/**
 * PHPUnit extension
 * @author Advertikon
 */

use PHPUnit\Framework\TestCase;

class myUnit extends TestCase {

	static $custom_handler = false;
	protected $old_error_handler = null;

	public function assertException( $assertion, $type = null ) {

		try {
			if ( is_callable( $assertion ) ) {
				$assertion();

			} else {
				trigger_error( 'Failed assert that exception was thrown - callable was not received' );
			}

		} catch ( \Exception $e ) {
			if ( ! is_null( $type ) ) {
				if( get_class( $e ) !== $type ) {	
					$this->fail(
						sprintf(
							'Failed to assert that exception of type "%s" was thrown. "%s" got instead',
							$type,
							get_class( $e )
						)
					);

				} else {
					$this->assertTrue( true );
					return;
				}

			} else {
				$this->fail(
					sprintf(
						'Failed to assert that exception of type %s belongs to some type - target type is missing',
						get_class( $e )
					)
				);
			}
		}

		$this->fail( 'Exception was not thrown' );
	}

	public function clean() {
		if ( ! self::$custom_handler ) {
			set_error_handler( array( $this, 'error_handler' ) );
			self::$custom_handler = true;
		}
	}

	public function clean_end() {
		if ( self::$custom_handler ) {
			restore_error_handler();
			self::$custom_handler = false;
		}
	}

	public function error_handler( $errno , $errstr, $errfile, $errline ) {
		throw new \Exception(
			sprintf(
				"\e[0;91mError was raised: %s in %s:%s\e[0m",
				$errstr, $errfile, $errline
			)
		);
	}
}
