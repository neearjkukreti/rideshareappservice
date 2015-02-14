<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ride extends CI_Controller {

    /**
     * Ride Page for this controller.
     *
     * Maps to the following URL
     * 		http://rideashare.com/index.php/ride
     * 	- or -  
     * 		http://rideashare.com/index.php/ride/create
     *
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('ride_model');
    }
    /**
     * This function is used to create the ride based on the given ride data via post 
     */
    public function create() {
        $post = $this->input->post();
        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
        $rideDirty = json_decode($HTTP_RAW_POST_DATA, true);
        if(!$rideDirty){
          $rideDirty = $post;  
        }
        $response = array();

        $ridedata = $this->cleanRide($rideDirty);

        $this->ride_model->create($ridedata);
        
        $response['status'] = 'CREATED';
        echo json_encode($response);
    }
    /**
     * This function is used to clean the dirty ride data to store the exactly same data as in ride_model
     * @param type $dirtyData
     * @return type
     */
    private function cleanRide($dirtyData) {
        $ride = array();
        if (isset($dirtyData['rdate'])) {
            $ride['rdate'] = $dirtyData['rdate'];
        }
        if (isset($dirtyData['rtime'])) {
            $ride['rtime'] = $dirtyData['rtime'];
        }
        if (isset($dirtyData['host'])) {
            $ride['host'] = $dirtyData['host'];
        }
        if (isset($dirtyData['car_id'])) {
            $ride['car_id'] = $dirtyData['car_id'];
        }
        if (isset($dirtyData['rfrom'])) {
            $ride['rfrom'] = $dirtyData['rfrom'];
        }
        if (isset($dirtyData['rto'])) {
            $ride['rto'] = $dirtyData['rto'];
        }
        if (isset($dirtyData['seats'])) {
            $ride['seats'] = $dirtyData['seats'];
        }
        if (isset($dirtyData['available_seats'])) {
            $ride['available_seats'] = $dirtyData['available_seats'];
        }
        if (isset($dirtyData['rlat'])) {
            $ride['rlat'] = $dirtyData['rlat'];
        }
        if (isset($dirtyData['rlong'])) {
            $ride['rlong'] = $dirtyData['rlong'];
        }
        if (isset($dirtyData['status'])) {
            $ride['status'] = $dirtyData['status'];
        }
        return $ride;
    }
    
    /**
     * This function is used to show the rides of user based on its type as 'offer' or 'apply'
     * @param type $userid
     */
    public function show($userid, $type) {
       
        $response = array();
        $output = $this->ride_model->readUserRides($userid, $type);
        if (!$output) {
            $response['status'] = 'fail';
        } else {
            $response['status'] = 'success';
            $response['rides'] = $output;
        }
        echo json_encode($response);
    }
    
    /**
     * This function will show the ride details of the given ride id.
     * @param type $rideid
     */
    public function showRide($rideid) {
        $response = array();
        $output = $this->ride_model->read($rideid);
        if (!$output) {
            $response['status'] = 'fail';
        } else {
            $response['status'] = 'success';
            $response['ride'] = $output;
        }
        echo json_encode($response);
    }
    
    public function apply(){
        $post = $this->input->post();
        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
        $rideDirty = json_decode($HTTP_RAW_POST_DATA, true);
        if(!$rideDirty){
          $rideDirty = $post;  
        }
        $ride_id = $rideDirty['rideid'];
        $user_id = $rideDirty['userid'];
        
        $response = array();
        
        $this->ride_model->apply($ride_id, $user_id);
        
        $response['status'] = 'APPLIED';
        echo json_encode($response);
    }
}
