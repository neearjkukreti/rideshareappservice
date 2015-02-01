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
        $this->car_model->read();
    }

    private function cleanCar($dirtyData) {
        $car = array();
        if (isset($dirtyData['plate_no'])) {
            $car['plate_no'] = $dirtyData['plate_no'];
        }
        if (isset($dirtyData['model'])) {
            $car['model'] = $dirtyData['model'];
        }
        if (isset($dirtyData['name'])) {
            $car['name'] = $dirtyData['name'];
        }
        if (isset($dirtyData['grade'])) {
            $car['grade'] = $dirtyData['grade'];
        }
        if (isset($dirtyData['sterio'])) {
            $car['sterio'] = $dirtyData['sterio'];
        }
        if (isset($dirtyData['rating'])) {
            $car['rating'] = $dirtyData['rating'];
        }
        if (isset($dirtyData['leather_seat'])) {
            $car['leather_seat'] = $dirtyData['leather_seat'];
        }

        if (isset($dirtyData['extra_info'])) {
            $car['extra_info'] = $dirtyData['extra_info'];
        }
        if (isset($dirtyData['user_id'])) {
            $car['user_id'] = $dirtyData['user_id'];
        }
        return $car;
    }
    public function show(){
        $this->car_model->read();   
    }

}
