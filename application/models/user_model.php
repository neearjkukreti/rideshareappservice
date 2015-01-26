<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class User_model extends CI_Model {
    const TABLE_USER            = 'user';
    
    const FIELD_FACEBOOK_ID     = 'fb_id';
    const FIELD_ID              = 'id';
    
    public function __construct(){
	$this->load->database();
    }
 
    public function create($userdata){
        $this->db->insert( self::TABLE_USER, $userdata );
        return $this->db->insert_id();
    }
    
    public function read($id){
        $sql = sprintf("SELECT * FROM %s WHERE id=%d", self::TABLE_USER, $id);
        $query = $this->db->query($sql);
        foreach($query->result() as $row){
            return $row;
        }
        return false;
    }
    
    public function update($userid, $userdata) {
        return true;
    }
    public function delete(){
        
    }
    
    public function isUserExists($id, $fieldname){
        if($fieldname == self::FIELD_FACEBOOK_ID){
            $sql = sprintf("SELECT * FROM %s WHERE %s=%s", self::TABLE_USER, $fieldname, $id);
            $result = $this->db->query($sql);
            if($result->num_rows() == 1){
                return true;
            }
        }
        return false;       
    }
}
