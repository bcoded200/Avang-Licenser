<?php
ob_start();//let us start the output buffering, to escape outbuffering header error
session_start();//a session is need in the application!


//call the composer autoloader file
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Before making an entry, we need to check if the application is clean!
 * that means we need to call the booted class to perform a crun job each time a request is sent
 * to the application.
 */
require_once 'src/Facdes/Booted.php';


