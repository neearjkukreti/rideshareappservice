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
        $carDirty = $post;
        $cardata = $this->cleanCar($carDirty);
        $this->car_model->create($cardata);
    }

    private function cleanCar($dirtyData) {
        return $dirtyData;
    }
    
    public function update() {
        $post = $this->input->post();
        $carDirty = $post;
        $cardata = $this->cleanCar($carDirty);
        $this->car_model->update($cardata['id'], $cardata);
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