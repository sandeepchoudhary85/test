# Modus Create PHP API Development Assignment

I have done the test

1) firstly you can take a pull from the repo
 
2) setup in your server

3)  Links 


  http://localhost/test_task/index.php/vehicles/2015/Audi/A3?withRating=true - GET

    http://localhost/test_task/index.php/vehicles?withRating=false - POST
    
/**** Post man data ****/
Import this in to posman 

    modelYear:2015
    manufacturer:Audi
    model:A3
    
 /**** Post man data ****/
 
 /*
 *
 * The mail Function is start 
 *
 */
 
 
 
    public function index()
    {
    //print_r($this->input->post('modelYear'));
    //die("test");
    $with_rating = $this->input->post('withRating', TRUE) ? $this->input->post('withRating', TRUE) : $this->input->get('withRating', TRUE);
    $year = $this->input->post('modelYear', TRUE)? $this->input->post('modelYear', TRUE) :$this->uri->segment(2);
    $make = $this->input->post('manufacturer', TRUE) ? $this->input->post('manufacturer', TRUE) : $this->uri->segment(3);
    $model = $this->input->post('model', true) ? $this->input->post('model', true) : $this->uri->segment(4);
    
    $jsn_data = file_get_contents("https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear".$year."/make/".$make."/model/".$model."?format=json");
    $ddata = json_decode($jsn_data);
    unset($ddata->Message);
    foreach($ddata->Results as $k=>$d){
    //$ddata->Results[$k]->VehicleId = $d->VehicleId;
    $ddata->Results[$k]->Description = $d->VehicleDescription;
    if($with_rating=="true"){
    $ratings = file_get_contents("https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId".$d->VehicleId."?format=json");
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




# test
# test
