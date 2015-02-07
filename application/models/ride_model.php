<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ride_model extends CI_Model {

    const TABLE_RIDE = 'ride';
    const TABLE_RIDE_BOOKING = 'ride_booking';
    const TABLE_USER = 'user';

    public function __construct() {
        $this->load->database();
    }

    public function create($ridedata) {
        $this->db->insert(self::TABLE_RIDE, $ridedata);
    }

    public function read($uid, $type) {
        if ($type == 'offer') {
            // show car hosted by user
            $sql = sprintf("SELECT * FROM %s r, %s rb WHERE r.id = rb.ride_id AND r.host = %d ", self::TABLE_RIDE, self::TABLE_RIDE_BOOKING, $uid);
        } elseif ($type == 'apply') {
            $sql = sprintf("SELECT * FROM %s r, %s rb WHERE r.id = rb.ride_id AND rb.user_id = %d ", self::TABLE_RIDE, self::TABLE_RIDE_BOOKING, $uid);
        } else {
            return false;
        }
        // show data joined by user
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $ridedata = array();
            foreach ($query->result_array() as $row) {
                $ridedata[] = $row;
            }
        } else {
            return false;
        }
        return $ridedata;
    }

    public function update() {
        
    }

    public function delete() {
        
    }

    public function search($rfrom = NULL, $rto = NULL, $rdate = NULL, $rtime = NULL, $extra_field = "") {

        $WHERE = " r.available_seats !=0 and r.host=u.id ";
        if ($rfrom != NULL){
            $WHERE .= sprintf(" AND r.rfrom = '%s' ", $rfrom);
        }

        if($rto != NULL){
            $WHERE .= sprintf(" AND r.rto='%s'", $rto);   
        }

        if($rdate != NULL ) {
            $WHERE .= sprintf(" AND r.rdate='%s' ", $rdate);         
        }

        if($rdate != NULL && $rtime != NULL) {
            $WHERE .= sprintf(" AND r.rtime='%s' ", $rtime);         
        }

        $sql = sprintf("SELECT r.*, u.id as user_id, u.firstname, u.lastname, u.gender, u.email_id, u.mobile, u.dob 
                              FROM %s r , %s u 
                              WHERE %s ", 
                            self::TABLE_RIDE, self::TABLE_USER, $WHERE );
        

        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $ridedata = array();
            foreach ($query->result_array() as $row) {
                $ridedata[] = $row;
            }
        } else {
            return false;
        }
        return $ridedata;
    }

}
