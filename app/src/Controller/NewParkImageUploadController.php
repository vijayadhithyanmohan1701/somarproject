<?php
namespace Doggo\Controller;

use Silverstripe\Control\Controller;
use Silverstripe\Control\HTTPRequest;

class NewParkImageUploadController extends Controller{
    private static $allowed_actions = [
        'parkImages',
        'index'
    ];
    private static $api_access = true;
    public function index(HTTPRequest  $request){
        echo "Index object";
    }
    public function parkImages(HTTPRequest $request){
        print_r($request->allParams());
    }
}