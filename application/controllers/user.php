<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    /**
	 * Search Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://rideashare.com/index.php/user
	 *	- or -  
	 * 		http://rideashare.com/index.php/user/create
	 *
	 */
        public function __construct() {
            parent::__construct();
            $this->load->model('user_model');
        }
        
        public function index()
        {
            
        }
        
        public function create(){
            $post = $this->input->post();
            $userDirty = $post;
            $userdata = $this->cleanUser($userDirty);
            $this->user_model->create($userdata);
        }
        
        private function cleanUser($dirtyData){
            $user = array();
            if(isset($dirtyData['id'])){
                $user['fb_id'] = $dirtyData['id'];
            }
            if(isset($dirtyData['first_name'])){
                $user['firstname'] = $dirtyData['first_name'];
            }
            if(isset($dirtyData['last_name'])){
                $user['lastname'] = $dirtyData['last_name'];
            }
            if(isset($dirtyData['gender'])){
                $user['gender'] = $dirtyData['gender'];
            }
            if(!isset($dirtyData['mobile'])){
                //$user['mobile'] = '9879879870';
            }
            if(!isset($dirtyData['dob'])){
                //$user['dob'] = '12/01/15';
            }
            if(!isset($dirtyData['email_id'])){
                //$user['email_id'] = 'test@example.com';
            }
            return $user;
        }
}

