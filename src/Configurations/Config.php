<?php

return [
    'app' => [
        'version' => '1.0.0',

        'names' => 'AVANG SECURITY',

        'author' => 'Christopher okoye',

       /**
        * replace with your api validation server.
        * make sure to define a column called license_key in the validation response 
        * you can work with the default database of this project.
        */
        'apiserver' => 'http://localhost/license',

        'apikey' => 'PUB_84748474747YX',
    ],

    'settings' => [

        /**
         * setting the license type to file will force the application to get the apikey from
         * storage/license.txt director. and use the directory as permanent storage directory for validation
         * through out the application. make sure you copy the license key to the license.txt file.
         * 
         * setting the license type to file will force the application to get the apikey from
         * config file located in configuration directory! or env file located in the configuration directory
         * make sure you copy the license key to the env or config  file.
         */
        'license_type' => 'enviroment',//values: enviroment or file

        'timezone' => 'Africa/lagos'

    ],
   
];
