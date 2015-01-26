<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    const GET_USER_ID               = 'USERID';
    const GET_FACEBOOK_ID           = 'FACEBOOKID';
    const STATUS_USER_CREATED       = 'USER_CREATED';
    const STATUS_USER_VERIFIED      = 'USER_VERIFIED';
    const STATUS_USER_UPDATED       = 'USER_UPDATED';
    
    const ERROR_CREATING_USER       = 'Error in creating user';
    /**
	 * Search Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://rideashare.com/index.php/user
	 *
	 */
        public function __construct() {
            parent::__construct();
            $this->load->model('user_model');
        }
        
        public function index()
        {
            $post = $this->input->post();
            $userDirty = $post;
            $userdata = $this->cleanUser($userDirty);
            
            $responseData = array();
            $userid = 0;
            if( $userid = $this->user_model->isUserExists( $userdata['fb_id'], User_model::FIELD_FACEBOOK_ID ) ){
                // Old User Updation and Verification here
                if( isset( $userDirty['needsUpdate']) ){
                    $this->user_model->update($userid, $userdata);
                    $responseData['status'] = self::STATUS_USER_UPDATED;
                }else{
                    $responseData['status'] = self::STATUS_USER_VERIFIED;
                }
            }else{
                // New User Creation Here
               $userid = $this->create($userdata); 
               if($userid){
                   $responseData['status'] = self::STATUS_USER_CREATED;
               }else{
                   $responseData['desc'] = self::ERROR_CREATING_USER;
               }
            }
            if($userid){
                $userBasicDetail = $this->user_model->read($userid);
                $responseData['userObject'] = $userBasicDetail;
            }
            echo json_encode($responseData);
        }
        
        private function create($userdata){
            $userid = $this->user_model->create($userdata);
            return $userid;
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
            if(isset($dirtyData['mobile'])){
                $user['mobile'] = $dirtyData['mobile'];
            }
            if(isset($dirtyData['dob'])){
               $user['dob'] = date( "Y-m-d", strtotime($dirtyData['dob']) );
            }
            if(isset($dirtyData['email_id'])){
               $user['email_id'] = $dirtyData['dob'];
            }
            return $user;
        }
}

