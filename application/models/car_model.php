<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Car_model extends CI_Model {

    const TABLE_CAR = 'car';
    
    const FIELD_ID = 'id';
    const FIELD_USERID = 'user_id';

    public function __construct() {
        $this->load->database();
    }

    public function create($carData) {
        $this->db->insert(self::TABLE_CAR, $carData);
    }

    public function read($id, $fieldName) {
        if( in_array( $fieldName, array(self::FIELD_ID, self::FIELD_USERID) ) ){
            $this->db->where($fieldName, $id);            
        }else{
            return false;
        }
        $query = $this->db->get(self::TABLE_CAR);
        $query->result();
    }

    public function update($id, $cardata) {
        $this->db->where('id', $id);
        $this->db->update(self::TABLE_CAR, $cardata);
    }

    public function delete($id) {
        $this->db->where(self::FIELD_ID, $id);
        $this->db->delete(self::TABLE_CAR);
    }
}
