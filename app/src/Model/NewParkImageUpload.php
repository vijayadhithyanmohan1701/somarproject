<?php

namespace Doggo\Model;
use Doggo\Model\NewPark;

class NewParkImageUpload extends \SilverStripe\ORM\DataObject
{
    private static $table_name = 'NewParkImageUpload';

    private static $db = [
        'Title' => 'Varchar',
        'Provider' => "Enum(array('Palmerston North City Council'))",
        'ParkProviderID' => 'Varchar(100)',
        'ImagePath' => 'Varchar(100)',
        'ShowImage' => 'Boolean'
    ];

    private static $summary_fields = [
        'Title' => 'Title',
    ];

    private static $has_one = [
        'ParkAssigned' => NewPark::class
    ];

    private static $indexes = [
        'Provider' => [
            'columns' => ['Provider'],
        ]
    ];
    private static $default_sort = 'Created';
    
    private static $api_access = true;

    public function canView($member = null)
    {
        return true;
    }
    public function canEdit($member = null) 
    {
        return true;
    }
    /*public function getEditForm($id = null, $fields = null) 
    {
        $form = parent::getEditForm($id, $fields);

        // $gridFieldName is generated from the ModelClass, eg if the Class 'Product'
        // is managed by this ModelAdmin, the GridField for it will also be named 'Product'
        $gridFieldName = $this->sanitiseClassName($this->modelClass);
        $gridField = $form->Fields()->fieldByName($gridFieldName);

        // modify the list view.
        $gridField->getConfig()->addComponent(new GridFieldFilterHeader());

        return $form;
    }*/
}
