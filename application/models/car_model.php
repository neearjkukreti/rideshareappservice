<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Car_model extends CI_Model {
   const TABLE_CAR = 'car';

    public function __construct(){
	$this->load->database();
    }
 
    public function create($cardata){
        $this->db->insert( self::TABLE_CAR, $cardata );
    }
    public function read(){
        $query = $this->db->get(self::TABLE_CAR);
        foreach ($query->result() as $row) {
            print_r($row);
        }
    }
    public function update() {
        
    }
    public function delete(){
        
    } 
}
