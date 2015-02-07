<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	/**
	 * Search Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://rideashare.com/index.php/search
	 *	- or -  
	 * 		http://rideashare.com/index.php/search/index
	 *
	 */

    public function __construct() {
        parent::__construct();
        $this->load->model('ride_model');
    }



	public function index()
	{
            
        $post = $this->input->post();
                    
        if (!isset($HTTP_RAW_POST_DATA)){
            $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        }
        
        $searchDirty = json_decode($HTTP_RAW_POST_DATA, true); 
        //$searchDirty = $post;

        $rfrom = $searchDirty['rfrom'];
        $rto   = $searchDirty['rto'];
        $rdate = $searchDirty['rdate'];
        $rtime = $searchDirty['rtime'];
        //$searchOrder = $searchDirty[''];

        $searchdata = $this->ride_model->search($rfrom ,$rto ,$rdate ,$rtime );

        $responseData['searchdata']=$searchdata;
        echo json_encode($responseData);
	}	
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */

