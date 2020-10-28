<?php
namespace Doggo\Task;

use Doggo\Model\NewPark;
use Doggo\Model\NewParkImageUpload;
use GuzzleHttp\Client;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class NewParksImageUpload extends BuildTask{
    private static $api_url;

    private static $api_title;
    private static $api_access = true;

    public function run($request){
        print_r($request);
        $parkId = $request->requestVar('ParkID');
        echo "ParkId:".$parkId;
        $parkObject = NewPark::get()->filter([
            'ID' => $parkId,
            'Provider' => 'Palmerston North City Council'
        ])->first();
        $parkId = $request->postVar('ParkID');
        header('Access-Control-Allow-Origin: *');  
        $filename = $_FILES['file']['name'];
        $allowed_extensions = array('jpg','jpeg','png','pdf');
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        //Had to use the absolute URL as localhost wont accept the relative ones
        
        $fileLoc = "c:wamp/www/somar/public/apiupload/IMG_2226.jpg";
        $parkImgUploadObject = NewParkImageUpload::create();
        $parkImgUploadObject->Title = $parkObject->Title;
        $parkImgUploadObject->Provider = $parkObject->Provider;
        $parkImgUploadObject->ParkProviderID = $parkObject->ProviderID;
        $parkImgUploadObject->ShowImage = false;
        $parkImgUploadObject->ImagePath = $fileLoc;
        $parkImgUploadObject->ParkAssignedID = $parkObject->ID;
        $parkImgUploadObject->write();
        if(in_array(strtolower($extension),$allowed_extensions) ) {     
            if(move_uploaded_file($_FILES['file']['tmp_name'], $fileLoc)){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }

    }
}