<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class User_model extends CI_Model {
    const TABLE_USER            = 'user';
    const TABLE_USER_DETAILS    = 'user_detail';
    
    const FIELD_FACEBOOK_ID     = 'fb_id';
    const FIELD_ID              = 'id';
    
    public function __construct(){
	$this->load->database();
    }
 
    public function create($userdata){
        $this->db->insert( self::TABLE_USER, $userdata );
        $lastid = $this->db->insert_id(); 
        $this->createUserDetails($lastid);
        return $lastid;
    }
    
    private function createUserDetails($userid){
        $userdata = array(  
                        'user_id'   => $userid,
                        'privacy'   =>  1,
                        'chat'      =>  0,
                        'pet'       =>  0,
                        'smoking'   =>  0,
                        'music'     =>  1,
                        'food'      =>  1,
            );
        $this->db->insert( self::TABLE_USER_DETAILS, $userdata );        
    }
    
    public function read($id){
        $sql = sprintf("SELECT id, firstname, lastname, gender, fb_id, email_id, mobile, dob "
                     . " FROM %s WHERE id=%d", 
                        self::TABLE_USER, $id);
        
        $query = $this->db->query($sql);
        if( $query->num_rows() ){
            $userdata = array();
            foreach ($query->result_array() as $row){
               $userdata = $row;
            }
            
            //User preferences to be added in user data 
            $sqlDetails = sprintf("SELECT user_id, privacy, lang_known, chat, music, smoking, food, pet "
                                . " FROM %s WHERE user_id=%d", self::TABLE_USER_DETAILS, $id);
            $queryTwo = $this->db->query( $sqlDetails );
            if( $queryTwo->num_rows() ){
                foreach ($queryTwo->result_array() as $preferences){
                    $userdata['privacy']    = $preferences['privacy'];
                    $userdata['lang_known'] = $preferences['lang_known'];
                    $userdata['chat']       = $preferences['chat'];
                    $userdata['music']      = $preferences['music'];
                    $userdata['smoking']    = $preferences['smoking'];
                    $userdata['food']       = $preferences['food'];
                    $userdata['pet']        = $preferences['pet'];
                }
            }
            return json_encode($userdata);
        }
        return false;
    }
    
    public function update($userid, $userdata) {
        $userUpdateArray = array('firstname', 'lastname', 'gender', 'email_id', 'mobile','dob');
        $userDataToBeUpdated = array();
        foreach( $userUpdateArray as $key){
            if( isset( $userdata[$key]  ) && trim($userdata[$key])!=""){
               $userDataToBeUpdated[$key] = $userdata[$key];
            }
        }
        if($userDataToBeUpdated){
            $this->db->where('id', $userid);
            $this->db->update(self::TABLE_USER, $userDataToBeUpdated);        
        }
        
        $userDetailsUpdate = array('lang_known','chat','music','smoking','food','pet');
        $userDetailsToBeUpdated = array();
        foreach( $userDetailsUpdate as $key){
            if( isset( $userdata[$key] ) ){
               $userDetailsToBeUpdated[$key] = $userdata[$key];
            }            
        }
        if($userDetailsToBeUpdated){
            $this->db->where('user_id', $userid);
            $this->db->update(self::TABLE_USER_DETAILS, $userDetailsToBeUpdated);        
        }
        return true;
    }
    public function delete(){
        
    }
    
    public function isUserExists($id, $fieldname){
        if($fieldname == self::FIELD_FACEBOOK_ID){
            $sql = sprintf("SELECT id FROM %s WHERE %s=%s", self::TABLE_USER, $fieldname, $id);
            $result = $this->db->query($sql);
            if($result->num_rows() == 1){
                return true;
            }
        }
        return false;       
    }
    
    public function getIDFromFacebookID($fb_id){
        $sql = sprintf("SELECT id FROM %s WHERE %s=%s", self::TABLE_USER, self::FIELD_FACEBOOK_ID, $fb_id);
        $result = $this->db->query($sql);
        foreach ($result->result_array() as $row){
            return $row['id'];
        }
        return false;
    }
}
