<?php

namespace Doggo\Admin;

use Doggo\Model\NewPark;
use Doggo\Model\NewParkImageUpload;


class NewParkAdmin extends \SilverStripe\Admin\ModelAdmin
{
    private static $managed_models = [
        NewPark::class
    ];

    private static $menu_title = 'New Parks';

    private static $url_segment = 'newparks';

}
