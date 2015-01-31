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

    public function create() {
        $post = $this->input->post();
        $rideDirty = $post;
        $ridedata = $this->cleanRide($rideDirty);
        $this->ride_model->create($ridedata);
    }

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
        if (isset($dirtyData['seat'])) {
            $ride['seats'] = $dirtyData['seats'];
        }
        if (isset($dirtyData['rlate'])) {
            $ride['rlat'] = $dirtyData['rlat'];
        }
        if (isset($dirtyData['rlong'])) {
            $ride['rlong'] = $dirtyData['rlong'];
        }
        return $ride;
    }



    public function show($uid) {

        $post = $this->input->post();

        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
            
        $post = json_decode($HTTP_RAW_POST_DATA, true); 
        $type = $post['type'];
        $response = array();
        $output = $this->ride_model->read($uid, $type);
        if(!$output){
            $response['status'] = 'fail';
        }else{
            $response['status'] = 'success';
            $response['rides'] = $output;
        }
        echo json_encode($response);

    }

}
