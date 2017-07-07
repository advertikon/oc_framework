<?php
/**
* Advertikon Stripe transport Exception
*
* @author Advertikon
* @package Stripe
<<<<<<< HEAD
* @version 0.0.7
=======
* @version 2.6.4
>>>>>>> afc80f7e39188e63f042e565011d01600f74032a
*/

namespace Advertikon\Exception;

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