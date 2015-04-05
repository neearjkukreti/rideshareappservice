<?php

class Carbon_model extends CI_Model {
	
	/**
	This function is used to get the carbon
	*/
	public function get_carbon( $count = 1 ){
		return $count * 10;
	}
}