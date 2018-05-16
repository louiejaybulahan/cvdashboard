<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of variables that are made specific to this application
    | that are better placed here rather than in .env file.
    | Use config('your_key') to get the values.
    |
    */

    //'company_name' => env('COMPANY_NAME','Acme Inc'),
    //'company_email' => env('COMPANY_email','contact@acme.inc'),

    'path_uploaded_data' => base_path('upload/data'),
    'path_filters_data' => base_path('upload/filters/'),
    'page_limit' => 50,
	
	//obtr v1.0 upload path
	'obtr_path_uploaded' => base_path('upload/obtrfile'),
	

];


/**
// You can access these variable using either of these two functions: 
Config::get('constants.company_name')
config('constants.company_name')
// For Blade as well: 
{{ config('constants.company_email') }}

*/