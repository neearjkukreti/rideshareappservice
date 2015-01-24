<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Ride_model extends CI_Model {
   const TABLE_RIDE = 'ride';

    public function __construct(){
	$this->load->database();
    }
 
    public function create($ridedata){
        $this->db->insert( self::TABLE_RIDE, $ridedata );
    }
    public function read(){
        
    }
    public function update() {
        
    }
    public function delete(){
        
    } 
}
