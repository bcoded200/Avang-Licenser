<?php
require_once 'inc.php';

use Bcoded\Avang\Facades\Booted;
use Carbon\Carbon;

// if(!class_exists('APP'));

class App extends Booted
{   
    public function render()
    {
        return $this->index();
    }

    public function index()
    {
        
       if(file_exists('storage/license'.$this->lextension))
       {

        $local_file = file_get_contents('storage/license'.$this->lextension);
        $server_file = $this->get($this->config('app.apiserver', $this->env('API_SERVER')));

        if (!empty($server_file) && is_array($server_file)) {
            $firstItem = $server_file[0]; // Access the first object in the array

            // Access the properties of the object
            $firstItem->license_key;

            //validation goes here
            if($local_file == $firstItem->license_key)
            {
                if($firstItem->expiry_date < Carbon::now()->timezone($this->config('settings.timezone')))
                {
                    $sourceFile = __DIR__ . '/welcome.php';
                    $destinationFolder = __DIR__ . '/storage/tmp/';
                    $this->destroy_source($sourceFile, $destinationFolder);
                    $this->flash('success', "Your license key has expired! renew your keys using the form below.");
                    return $this->redirect('activate.php');
                   //return $this->abort(403,'Your license key is not valid!');
                }


                if(is_file('welcome'.$this->extension))
                {
                    $newlocation = 'welcome'.$this->extension;
                    return $this->redirect($newlocation);
            
                }
                else
                {//redirect to license submission form 
                    $this->flash('error', "Your application root is broken or mising. kindly contact support for quick fix.");
                    return $this->redirect('activate.php');
                }
    
            }else
            {
                    $sourceFile = __DIR__ . '/welcome.php';
                    $destinationFolder = __DIR__ . '/storage/tmp/';
                    $this->destroy_source($sourceFile, $destinationFolder);
                    $this->flash('error', "Your license key is not valid. kindly check your input and try again.");
                    return $this->redirect('activate.php');
                //return $this->abort(403,'Your license key is not valid!');
            }

    
        } else {
            /**
             * possible cause to 490:
             * incorrect apikey
             * apikey is empty
             * no config or env specifed for the apikey
             */
            $this->flash('error', "the api server is not accessed correctly refrence to error code 490 on the documentation.");
            return $this->redirect('activate.php');

        }

       }
   
    }


  
    }



$app = new App;
$app->render();


       
        
        
        
        
        


