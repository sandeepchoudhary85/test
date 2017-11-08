<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	/*function _remap($parameter){
	   $this->index($parameter);
	}*/
	public function index()
	{
		//print_r($this->input->post('modelYear'));
		//die("test");
		$with_rating = $this->input->post('withRating', TRUE) ? $this->input->post('withRating', TRUE) : $this->input->get('withRating', TRUE);
		$year = $this->input->post('modelYear', TRUE)? $this->input->post('modelYear', TRUE) :$this->uri->segment(2);
		$make = $this->input->post('manufacturer', TRUE) ? $this->input->post('manufacturer', TRUE) : $this->uri->segment(3);
		$model = $this->input->post('model', true) ? $this->input->post('model', true) : $this->uri->segment(4);

		$jsn_data = file_get_contents("https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/".$year."/make/".$make."/model/".$model."?format=json");
		$ddata = json_decode($jsn_data);		
		unset($ddata->Message);
		foreach($ddata->Results as $k=>$d){
			//$ddata->Results[$k]->VehicleId = $d->VehicleId;
			$ddata->Results[$k]->Description = $d->VehicleDescription;
			if($with_rating=="true"){
				$ratings = file_get_contents("https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/".$d->VehicleId."?format=json");
				$ratings = json_decode($ratings);
				if($ratings->Count != "0"){
					$ddata->Results[$k]->CrashRating = $ratings->Results[0]->OverallRating;
				}else{
					$ddata->Results[$k]->CrashRating = "Not Rated";
				}
			}			
			unset($ddata->Results[$k]->VehicleDescription);
		}

		echo json_encode($ddata);
		die;
	}
}
