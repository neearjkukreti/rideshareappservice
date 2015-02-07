<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Car extends CI_Controller {

    /**
     * car Page for this controller.
     *
     * Maps to the following URL
     * 		http://carashare.com/index.php/car
     * 	- or -  
     * 		http://carashare.com/index.php/car/create
     *
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('car_model');
    }

    public function create() {
        $post = $this->input->post();
        
        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
        $carDirty = json_decode($HTTP_RAW_POST_DATA, true);
        $response = array();
        if( !isset( $carDirty['user_id']) ){
            $response['error'] = 'Missing user data';
            echo json_encode($response);
            return;
        }
        
        //$carDirty = $post;
        $cardata = $this->cleanCar($carDirty);
        $this->car_model->create($cardata);
        
        $userCarDetails = $this->car_model->read( $carDirty['user_id'], 'user_id' );
        if( $userCarDetails ){
            $response['status'] = 'CREATED';
            $response['cars']   = $userCarDetails;
        }
        echo json_encode($response);
    }

    private function cleanCar($dirtyData) {
        $cleanData = array();
        if(isset($dirtyData['id'])){
            $cleanData['id']                = $dirtyData['id'];
        }
        if(isset($dirtyData['plate_no'])){
            $cleanData['plate_no']          = $dirtyData['plate_no'];
        }
        if(isset($dirtyData['model'])){
            $cleanData['model']             = $dirtyData['model'];
        }
        if(isset($dirtyData['name'])){
            $cleanData['name']              = $dirtyData['name'];
        }
        if(isset($dirtyData['grade'])){
            $cleanData['grade']             = $dirtyData['grade'];
        }
        if(isset($dirtyData['stereo'])){
            $cleanData['stereo']            = $dirtyData['stereo'];
        }
        if(isset($dirtyData['leather_seat'])){
            $cleanData['leather_seat']      = $dirtyData['leather_seat'];
        }
        if(isset($dirtyData['user_id'])){
            $cleanData['user_id']           = $dirtyData['user_id'];
        }
        if(isset($dirtyData['rating'])){
            $cleanData['rating']            = $dirtyData['rating'];
        }
        if(isset($dirtyData['extra_info'])){
            $cleanData['extra_info']            = $dirtyData['extra_info'];
        }
        if(isset($dirtyData['ac'])){
            $cleanData['ac']            = $dirtyData['ac'];
        }
        return $cleanData;
    }
    
    public function update() {
        //$post = $this->input->post();
        //$carDirty = $post;
        
        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
        $carDirty = json_decode($HTTP_RAW_POST_DATA, true);
        if( !isset( $carDirty['user_id']) || !isset( $carDirty['id'] ) ){
            $response['error'] = 'Missing car data';
            echo json_encode($response);
            return;
        }
        
        $carData = $this->cleanCar($carDirty);
        $this->car_model->update($carData['id'], $carData);
        
        $userCarDetails = $this->car_model->read( $carData['user_id'], 'user_id' );
        if( $userCarDetails ){
            $response['status'] = 'UPDATED';
            $response['cars']   = $userCarDetails;
        }
        echo json_encode($response);
    }

    public function delete() {        
        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
        $carDirty = json_decode($HTTP_RAW_POST_DATA, true);
        if( !isset( $carDirty['id'] ) ){
            $response['error'] = 'Missing car data';
            echo json_encode($response);
            return;
        }
        
        $carData = $this->cleanCar($carDirty);
        $this->car_model->delete($carData['id']);
        
        $response['status'] = 'DELETED';

        if( isset( $carData['user_id'] ) ){
            $userCarDetails = $this->car_model->read( $carData['user_id'], 'user_id' );
            if( $userCarDetails ){
                $response['cars']   = $userCarDetails;
            }
        }
        echo json_encode($response);
    }

    public function getDetails() {
        //$post = $this->input->post();
        
        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
        $carDirty = json_decode($HTTP_RAW_POST_DATA, true);
        
        $id = $carDirty['id'];
        $fieldName = $carDirty['type'];
        $output = $this->car_model->read($id, $fieldName);
        if($output){
            $responseData['details'] = $output;
        }else{
            $responseData['status'] = 'fail';
        }        
        echo json_encode($responseData);
    }
}