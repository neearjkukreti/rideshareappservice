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
    
    /**
     * This model function is used to retrieve the ride data based on the given ride id 
     * @param type $id
     * @return boolean
     */
    public function read($id) {
        $sql = sprintf("SELECT * FROM %s r "
                        . "WHERE r.id = %d ", 
                        self::TABLE_RIDE, $id);
        
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $ridedata = array();
            foreach ($query->result_array() as $row) {
                $ridedata = $row;
            }
        } else {
            return false;
        }
        return $ridedata;
    }
    
    /**
     * This model function is used to retrieve the ride data of the user based on its type i.e offer/apply
     * @param type $userid
     * @param type $type
     * @return boolean
     */
    public function readUserRides($userid, $type) {
        if ($type == 'offer') {
            // show car hosted by user
            $sql = sprintf("SELECT * FROM %s r WHERE host = %d ", self::TABLE_RIDE, $userid);            
        } elseif ($type == 'apply') {
            $sql = sprintf("SELECT * FROM %s r, %s rb WHERE r.id = rb.ride_id AND rb.user_id = %d ", self::TABLE_RIDE, self::TABLE_RIDE_BOOKING, $userid);
        } else {
            return false;
        }
        // show data joined by user
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $ridedata = array();
            foreach ($query->result_array() as $row) {
                if($type == 'offer'){
                    $row['bookingDetails'] = $this->readBookingDetails( $row['id'] );
                }
                $ridedata[] = $row;
            }
        } else {
            return false;
        }
        return $ridedata;
    }
    
    private function readBookingDetails( $rideid ) {
        $sqlbookingDetails = sprintf("SELECT u.* FROM %s rb, %s u "
                                   . "WHERE rb.ride_id = %d AND u.id=rb.user_id", 
                                    self::TABLE_RIDE_BOOKING, self::TABLE_USER, $rideid);
        $queryBooking = $this->db->query($sqlbookingDetails);
        $bookingDetails = array();
        if ($queryBooking->num_rows()) {
            foreach ($queryBooking->result_array() as $row) {
                $bookingDetails[] = $row;
            }
        }
        return $bookingDetails;
    }
    
    public function update() {
        
    }

    public function delete() {
        
    }
    /**
     * This model function is used for searching the rides on the basis of the given parameters
     * Also if you gave null params then it returns all the available rides.
     * @param type $rfrom
     * @param type $rto
     * @param type $rdate
     * @param type $rtime
     * @param type $extra_field
     * @return boolean
     */
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

/**
     * This model function is used for searching the top 3 riders and top 3 ride taker
 */

    public function topthree(){


      //  select u.id, count(u.id) count From ride_booking rb, user u where u.id = user_id group by u.id order by count desc limit 0,3

        $sqltoprider =  sprintf("SELECT u.id,u.firstname,u.lastname,count(u.id) count  FROM %s rb, %s u "
                                   . "WHERE u.id =user_id group by u.id order by count desc limit 0,3", 
                                    self::TABLE_RIDE_BOOKING, self::TABLE_USER);
        $querytoprider = $this->db->query($sqltoprider);
      
       if($querytoprider->num_rows()){
         $topthreerider=array();
         foreach ($querytoprider->result_array() as $row){
            $topthreerider[]=$row;
         }
     }
         else{
               $topthreerider=array();
         }


    //select u.id, count(u.id) count from user u, ride r where u.id = r.host or u.id group by u.id order by count desc limit 0,3

         $sqltopridetaker =  sprintf("SELECT u.id,u.firstname,u.lastname,count(u.id) count  FROM %s u, %s r "
                                   . "WHERE u.id =r.host or u.id  group by u.id order by count desc limit 0,3", 
                                    self::TABLE_USER, self::TABLE_RIDE);

         $querytoptaker = $this->db->query($sqltopridetaker);
      
       if($querytoptaker->num_rows()){
         $topthreetaker=array();
         foreach ($querytoptaker->result_array() as $row){
            $topthreetaker[]=$row;
         }
     }
         else{
               return false;
         }


            $total=$topthreerider+$topthreetaker ;
            $result = count($total);

            if($result=0)
            {
              $topthreetaker=array();
               
            }
            else
            {
                return $total;
            }
            

    }






    public function apply($ride_id, $user_id){
        $bookingData = array(
                            'ride_id' => $ride_id, 
                            'user_id' => $user_id,
                            'booking_status' => 2 
                            // booking status 
                            // 0: Canceled by Host, 
                            // 1: Confirmed by Host,
                            // 2: Confirmed by Applied User,
                            // 3: Canceled by Applied User
                            );
        $this->db->insert(self::TABLE_RIDE_BOOKING, $bookingData);
    }

}
