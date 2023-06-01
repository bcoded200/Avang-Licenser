<?php

namespace Bcoded\Avang\Facades;

class Booted
{

    private $url;

    public $extension = '.php';
    public $lextension = '.txt';


    public function __construct()
    {
    }

    public function readfile($filename)
    {
        $fileContents = file_get_contents($filename);

        return  $fileContents;
    }

    public function validate($local_key, $server_key)
    {
        if ($local_key == $server_key) {
            return true;
        }
    }

    public static function get($url)
    {
        $object = new self;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }


    public function render()
    {
    }


    public function redirect($newlocation)
    {
        header('Location:' . $newlocation);
        exit();
    }

    /**
     * flash a piece of data to the client. this method is deprecated!! use the flash() & get() method instead
     *
     */

    public function session_set()
    {
        if (isset($_SESSION['success'])) {
            $result = "<div class='alert alert-success'>";
            $result .= htmlentities($_SESSION['success']);
            $result .= '</div>';
            $_SESSION['success'] = null;
            return $result;
        }
    }

    public function flash($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function gets($key)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }

        return null;
    }

    function abort($statusCode, $message = '')
    {
        http_response_code($statusCode);
        if (!empty($message)) {
            echo $message;
        }
        exit();
    }

    public function config($key, $defaultValue = null)
    {
        $configFile =  __DIR__ . '../../Configurations/Config.php';

        if (file_exists($configFile)) {
            $config = include $configFile;
            $keys = explode('.', $key);

            foreach ($keys as $key) {
                if (isset($config[$key])) {
                    $config = $config[$key];
                } else {
                    return $this->env($key, $defaultValue);
                }
            }

            return $config;
        }

        return $this->env($key, $defaultValue);
    }

    public function env($key, $defaultValue = null)
    {
        $envFile =  __DIR__ . '../../Configurations/.env';



        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            $envLines = explode(PHP_EOL, $envContent);

            foreach ($envLines as $line) {
                $line = trim($line);

                if (!empty($line) && strpos($line, '=') !== false) {
                    [$envKey, $envValue] = explode('=', $line, 2);


                    if ($key === $envKey) {
                        return $envValue;
                    }
                }
            }
        }

        $value = getenv($key);

        if ($value !== false) {
            return $value;
        }

        return $defaultValue;
    }

    public function destroy_source($sourceFile, $destinationFolder)
    {
        //         $sourceFile = __DIR__ . '/welcome.php';
        // $destinationFolder = __DIR__ . '/storage/tmp/';
        $destinationFile = $destinationFolder . 'welcome.php';

        // Check if the source file exists
        if (file_exists($sourceFile)) {
            // Create the destination folder if it doesn't exist
            if (!file_exists($destinationFolder)) {
                mkdir($destinationFolder, 0777, true);
            }

            // Move the file to the destination folder
            if (rename($sourceFile, $destinationFile)) {
                echo 'File moved successfully.';
            } else {
                echo 'Failed to move the file.';
            }
        } else {

            //not found! means script is not valid anymore

            $this->flash('error', "Your application root is broken or mising. kindly contact support for quick fix.");
            return $this->redirect('activate.php');

            //OR

            //return $this->abort(403, 'FORBIDDEN: You are not authorized to use this script');

        }
    }

    
}
