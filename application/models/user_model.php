<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class User_model extends CI_Model {
    const TABLE_USER = 'app_user';

    public function __construct(){
	$this->load->database();
    }
 
    public function create($userdata){
        $this->db->insert( self::TABLE_USER, $userdata );
    }
    public function read(){
        
    }
    public function update() {
        
    }
    public function delete(){
        
    }
}
