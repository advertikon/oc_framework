<?php
use PHPUnit\Framework\TestCase;

class TestListener implements PHPUnit_Framework_TestListener {

	public function __construct() {

	}

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // printf("Error while running test '%s'.\n", $test->getName());
    }

    public function addFailure( PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time ) {
    	if ( ! method_exists( $e, 'getComparisonFailure' ) ) {
    		return;
    	}

    	$f = $e->getComparisonFailure();

    	if ( ! method_exists( $f, 'getActual' ) ) {
    		return;
    	}
    	$a = $f->getActual();
    	$e = $f->getExpected();

    	if ( is_string( $e ) && is_string( $a ) && strlen( $a ) > 20 ) {
    		$len = max( strlen( $a ), strlen( $e ) );
    		$l1 = '-';
    		$l2 = '+';
    		$diff = false;

    		for( $i = 0; $i < $len; $i++ ) {
    			$s1 = isset( $e[ $i ] ) ? $e[ $i ] : '';
    			$s2 = isset( $a[ $i ] ) ? $a[ $i ] : '';

    			if ( $s1 !== $s2 ) {
    				if ( ! $diff ) {
    					$l1 .= "\e[41m";
    					$l2 .= "\e[41m";
    				}

    				$diff = true;

    			} else {
    				if ( $diff ) {
    					$l1 .= "\e[0m";
    					$l2 .= "\e[0m";
    				}

    				$diff = false;
    			}

    			$l1 .= $s1;
    			$l2 .= $s2;
    		}

    		$l1 .= "\e[0m";
    		$l2 .= "\e[0m";

    		echo PHP_EOL . $l1 . PHP_EOL . $l2 . PHP_EOL;
    	}
    	// var_dump( get_class_methods( $test ) );
    	//var_dump( $test->getResult(), $test->returnValue() );
    	//var_dump( get_class_methods( $e->getComparisonFailure() ) );
    	//echo $e->getComparisonFailure()->getActual() . PHP_EOL . $e->getComparisonFailure()->getDiff() . PHP_EOL;
        // printf("Test '%s' failed.\n", $test->getName());
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
       // printf("Test '%s' is incomplete.\n", $test->getName());
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // printf("Test '%s' is deemed risky.\n", $test->getName());
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // printf("Test '%s' has been skipped.\n", $test->getName());
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
        // printf("Test '%s' started.\n", $test->getName());
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        // printf("Test '%s' ended.\n", $test->getName());
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        // printf("TestSuite '%s' started.\n", $suite->getName());
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        // printf("TestSuite '%s' ended.\n", $suite->getName());
    }
}
?>
