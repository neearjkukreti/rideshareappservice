<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    const GET_USER_ID               = 'USERID';
    const GET_FACEBOOK_ID           = 'FACEBOOKID';
    const STATUS_USER_CREATED       = 'USER_CREATED';
    const STATUS_USER_VERIFIED      = 'USER_VERIFIED';
    const STATUS_USER_UPDATED       = 'USER_UPDATED';
    
    const ERROR_CREATING_USER       = 'Error in creating user';
    const ERROR_FACEBOOK_USER       = 'Error in getting facebook id';
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
                        
            if (!isset($HTTP_RAW_POST_DATA)){
                $HTTP_RAW_POST_DATA = file_get_contents("php://input");
            }
            
            $userDirty = json_decode($HTTP_RAW_POST_DATA, true); 
            //$userDirty = $post;
            
            $userdata = $this->cleanUser($userDirty);
            $responseData = array();
            if(!isset( $userdata['fb_id']) ){
                $responseData['desc'] = self::ERROR_FACEBOOK_USER;
                echo json_encode($responseData);
                return;
            }            
            $userid = 0;
            if( $this->user_model->isUserExists( $userdata['fb_id'], User_model::FIELD_FACEBOOK_ID ) ){// Old User Updation and Verification here
                $userid = $this->user_model->getIDFromFacebookID( $userdata['fb_id']);
                $responseData['status'] = self::STATUS_USER_VERIFIED;                
                if( isset( $userDirty['fb_id']) ){
                    $this->user_model->update($userid, $userdata);
                    $responseData['status'] = self::STATUS_USER_UPDATED;
                }
            }else{ // New User Creation Here
               $userid = $this->create($userdata);
               $responseData['desc'] = self::ERROR_CREATING_USER;
               if($userid){
                   $responseData['status'] = self::STATUS_USER_CREATED;
                   $responseData['desc'] = "";
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
        
        private function update($userdata){
            $this->user_model->update($userdata);             
        }
        
        private function cleanUser($dirtyData){                        
            if(isset($dirtyData['fb_id']) ){
                return $this->cleanUserForUpdation($dirtyData); 
            }else{
                return $this->cleanUserForInsertion($dirtyData);
            }
        }
        
        private function cleanUserForUpdation($dirtyData){
            $user = array();
            $userUpdateArray = array('id','firstname', 'lastname', 'gender', 'fb_id', 'email_id', 'mobile','dob',
                                     'lang_known','chat','music','smoking','food','pet');
            foreach($userUpdateArray as $key){
                if( isset($dirtyData[$key]) ){
                    $user[$key] =  $dirtyData[$key];
                }elseif(in_array($key, array('chat','music','smoking','food','pet'))){
                    $user[$key] = 0;
                }
            }
            return $user;
        }
        
        private function cleanUserForInsertion($dirtyData){
            //$userVerified = array('fb_id','firstname', 'lastname', 'gender', 'email_id', 'mobile','dob');
            $user = array();
            $facebookUser = array('id','first_name','last_name','gender','mobile','dob','email_id');
            foreach( $facebookUser as $key){
                if( isset( $dirtyData[$key] ) ){
                    if( !in_array($key, array('id','first_name','last_name') ) ){
                        if($key == 'dob'){
                            $user['dob'] = date( "Y-m-d", strtotime($dirtyData['dob']) );
                        }else{
                            $user[$key] = $dirtyData[$key];
                        }                            
                    }elseif($key == 'id') {
                        $user['fb_id'] = $dirtyData[$key];
                    }elseif($key == 'first_name'){
                        $user['firstname'] = $dirtyData[$key];
                    }elseif($key == 'last_name'){
                        $user['lastname'] = $dirtyData[$key];
                    }
                }
            }
            return $user;
        }
}

