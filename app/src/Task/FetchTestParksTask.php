<?php

namespace Doggo\Task;

use Doggo\Model\NewPark;
use GuzzleHttp\Client;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class FetchTestParksTask extends BuildTask
{
    private static $api_url;

    private static $api_title;

    public function run($request)
    {
        $client = new Client();

        $response = $client->request(
            'GET',
            $this->config()->get('api_url'),
            ['User-Agent' => 'Doggo (www.somar.co.nz)']
        );

        if ($response->getStatusCode() !== 200) {
            user_error('Could not access ' . $this->config()->get('api_url'));
            exit;
        }

        $existingParks = NewPark::get()->filter('Provider', $this->config()->get('api_title'));
        foreach ($existingParks as $park) {
            $park->IsToPurge = true;
            $park->write();
        }

        $data = json_decode($response->getBody());
        $parks = $data->features;
        foreach ($parks as $park) {
          
            if(!(is_null($park->geometry) || $park->properties->RESERVE_NAME === '')){
                
                echo $park->properties->RESERVE_NAME." <br>";
                $geometry = $park->geometry->coordinates;
                $parkObject = NewPark::get()->filter([
                    'Provider' => $this->config()->get('api_title'),
                    'ProviderID' => $park->properties->OBJECTID,
                ])->first();
                $status = 'changed';
    
                if (!$parkObject) {
                    $status = 'created';
                    $parkObject = NewPark::create();
                    $parkObject->Provider = $this->config()->get('api_title');
                    $parkObject->ProviderID = $park->properties->OBJECTID;
                }
    
                if ($park->properties->DESCRIPTION === 'Dog exercise area') {
                    $leash = 'Off-leash';
                } elseif ($park->properties->DESCRIPTION === 'Dogs prohibited') {
                    continue;
                } else {
                    $leash = 'On-leash';
                }
                if($park->properties->RESERVE_NAME !== ''){
                    $parkObject->update([
                        'IsToPurge' => false,
                        'Title' => $park->properties->RESERVE_NAME,
                        'Latitude' => $geometry[0][0][0],
                        'Longitude' => $geometry[0][0][1],
                        'Notes' => $park->properties->DESCRIPTION,
                        'GeoJson' => json_encode($park),
                        'FeatureOnOffLeash' => $leash,
                    ]);
        
                    $parkObject->write();
        
                    DB::alteration_message('[' . $parkObject->ProviderID . '] ' . $parkObject->Title, $status);

                }else{
                    continue;
                }
            }
            
        }

        $existingParks = NewPark::get()->filter([
            'Provider' => $this->config()->get('api_title'),
            'IsToPurge' => true,
        ]);
        foreach ($existingParks as $park) {
            DB::alteration_message('[' . $parkObject->ProviderID . '] ' . $parkObject->Title, 'deleted');
            $park->delete();
        }
    }
}
