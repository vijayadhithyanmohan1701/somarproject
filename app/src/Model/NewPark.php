<?php

namespace Doggo\Model;
use Doggo\Model\NewParkImageUpload;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class NewPark extends \SilverStripe\ORM\DataObject
{
    private static $table_name = 'NewPark';

    private static $db = [
        'Title' => 'Varchar',
        'Latitude' => 'Decimal(9,6)',
        'Longitude' => 'Decimal(9,6)',
        'Notes' => 'Text',
        'Provider' => "Enum(array('Palmerston North City Council'))",
        'ProviderID' => 'Varchar(100)',
        'GeoJson' => 'Text',
        'FeatureOnOffLeash' => "Enum(array('On-leash', 'Off-leash'), 'On-leash')",
        'IsToPurge' => 'Boolean',
    ];
    private static $has_many = [
        'UploadedImages' => NewParkImageUpload::class
    ];
    private static $summary_fields = [
        'Title' => 'Title',
    ];
    private static $indexes = [
        'Provider' => [
            'columns' => ['Provider'],
        ],
        'ProviderCode' => [
            'columns' => ['Provider', 'ProviderID'],
        ],
    ];
    private static $default_sort = 'Title';

    private static $api_access = true;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.UploadedImages', GridField::create(
            'UploadedImages',
            'Images Uploaded',
            $this->UploadedImages(),
            GridFieldConfig_RecordEditor::create()
        ));

        return $fields;
    }

    public function validate()
    {
        $validate = parent::validate();

        if (empty($this->Title)) {
            $validate->addFieldError('Title', 'Title is required');
        }

        return $validate;
    }

    public function canView($member = null)
    {
        return true;
    }
    public function canEdit($member = null) 
    {
        return true;
    }
}
