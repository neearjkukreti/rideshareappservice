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
	public function index()
	{
            
            //$rideDate =  $this->input->post('rideDate');
	    //$sourceCity = $this->input->post('sourceCity');
            //$destinationCity = $this->input->post('destinationCity');
            $sourceCity = "Delhi";
            $destinationCity = "Dehradun City";
            
            $responseData = array(
                                  "basic" =>  array("SourceCity" => $sourceCity,
                                                    "DestinationCity" => $destinationCity,
                                                    "Distance"  => "255.90",
                                                    "Unit"  => "KM",
                                                    "TimeinMinutes"  => "285",
                                                    "AverageFare"   => "650",
                                                    "Currency"  => "Rupees",
                                                    "Ridedate"  => "24/01/2015"
                                                    ),
                                  "totalAvailabilty" => 23,
                                  "showResults" => 5,
                                  "pageNo"  => 1, 
                                  "availability" => array(
                                                        "2001" => array(
                                                                        "Name" => "Dilaram R. Singh",
                                                                        "ShortDescription" => "I am a IT professional, working as manager",
                                                                        "Age"   => "34",
                                                                        "Timein24format"  => "13:30:00",
                                                                        "ProfilePic" => "http://rideashare.com/images/user/images2001.jpg",
                                                                        "facebookid" => "100010001001"
                                                                    ),
                                                        "2002" => array(
                                                                        "Name" => "Radhe L. Singh",
                                                                        "ShortDescription" => "I am a Woodland professional, working as Bootmaker",
                                                                        "Age"   => "24",
                                                                        "Timein24format"  => "10:00:00",
                                                                        "ProfilePic" => "http://rideashare.com/images/user/images2002.jpg",
                                                                        "facebookid" => "100010001111"
                                                                    ),
                                                        "2013" => array(
                                                                        "Name" => "Rakshat kumar",
                                                                        "ShortDescription" => "Shop owner",
                                                                        "Age"   => "38",
                                                                        "Timein24format"  => "12:00:00",
                                                                        "ProfilePic" => "http://rideashare.com/images/user/images2013.jpg",
                                                                        "facebookid" => "100010001341"
                                                                    ),
                                                        "2019" => array(
                                                                        "Name" => "Mohd. RafiQ",
                                                                        "ShortDescription" => "Flower BoutiQue",
                                                                        "Age"   => "41",
                                                                        "Timein24format"  => "09:00:00",
                                                                        "ProfilePic" => "http://rideashare.com/images/user/images2019.jpg",
                                                                        "facebookid" => "1009010001341"
                                                                    ),
                                                        "2102" => array(
                                                                        "Name" => "George Hawkins",
                                                                        "ShortDescription" => "Motor Cyclist",
                                                                        "Age"   => "31",
                                                                        "Timein24format"  => "11:00:00",
                                                                        "ProfilePic" => "http://rideashare.com/images/user/images2102.jpg",
                                                                        "facebookid" => "100016001341"
                                                                    ),
                                                    )
                            );
            echo json_encode($responseData);
	}	
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */

