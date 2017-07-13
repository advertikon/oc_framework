<?php
/**
* Advertikon Stripe transport Exception
*
* @author Advertikon
* @package Stripe
* @ver0.0.0      2.6.4
mespace Advertikon\Exception;

use Advertikon\Exception as AdvertikonException;

class Transport extends AdvertikonException {

	protected $_data;

	public function __construct( $data ){
		if( is_scalar( $data ) ) {
			parent::__construct( $data );
		}
		else {
			$this->_data = $data;
			parent::__construct( '' );
		}
	}

	/**
	* Get transported object
	*
	* @return mixed
	*/
	public function getData(){
		return $this->_data;
	}
}