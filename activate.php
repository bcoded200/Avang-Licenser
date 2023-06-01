<?php
require_once 'inc.php';

use Bcoded\Avang\Facades\Booted;

$app = new Booted;


if (isset($_POST['submit'])) {
    $license_key = isset($_POST['license_key']) ? $_POST['license_key'] : '';
    $license_domain = isset($_POST['license_domain']) ? $_POST['license_domain'] : '';

    $ch = curl_init($app->config('app.apiserver', $app->env('API_SERVER')));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    $response = curl_exec($ch);
    curl_close($ch);

    // Parse the response as JSON and convert it to an array
    $result = json_decode($response);

    // Check if decoding was successful
    if ($result === null) {
        // Handle JSON decoding error
        dd('decoding errror');
        // ...
    } else {
        // Iterate over the array and access the objects
        foreach ($result as $object) {
            // Access properties of each object
            $id = $object->id;
            $licenseKey = $object->license_key;
            $expiryDate = $object->expiry_date;
            $domainOrigin = $object->domain_origin;

            if ($license_key == $licenseKey) {

                
                $sourcePath = './storage/tmp/welcome.php';
                $destinationPath = './welcome.php';

                // Check if the source file exists
                if (file_exists($sourcePath)) {
                    // Move the file to the destination
                    if (rename($sourcePath, $destinationPath)) {

                       $app->flash('success',"App restored successfully");

                       //write the key to the env file

                       // Update the .env file
    $envFilePath = __DIR__ . './Configurations/.env';
    $envContent = file_get_contents($envFilePath);
    $updatedEnvContent = preg_replace("/^API_KEY=.*$/m", "API_KEY={$license_key}", $envContent);

    $envFileHandle = fopen($envFilePath, 'w');
    fwrite($envFileHandle, $updatedEnvContent);
    fclose($envFileHandle);

    // Update the Config.php file
    $configFilePath = __DIR__ . './Configurations/Config.php';
    $configContent = file_get_contents($configFilePath);
    $updatedConfigContent = preg_replace("/'apikey' => '.*'/", "'apikey' => '{$license_key}'", $configContent);

    $configFileHandle = fopen($configFilePath, 'w');
    fwrite($configFileHandle, $updatedConfigContent);
    fclose($configFileHandle);

                        ##############END

                       //write the key to the license txt file
                        $filePath = __DIR__ . '/storage/license.txt';
                        $fileContent = $license_key;

                        // Save the content to the file
                        file_put_contents($filePath, $fileContent);

                        // Output a success message
                        echo "License key saved successfully to txt.";

                       return $app->redirect($destinationPath);
                    } else {

                        $app->flash('error',"Unable to migrate your app. contact adminstrator if this issue persisit.");
                        return $app->redirect('./activate.php');
                    }
                } else {
                    $app->flash('error',"Unable to locate the migration file. refrence to error 904 in Avang documentation.");
                    return $app->redirect('./activate.php');
                }
            }else
            {
                $app->flash('error',"Whoops! your apikey is incorrect or missing paremeter. reference to error 765 of Avang documentation");
                return $app->redirect('./activate.php');

            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>License Activation</title>
    <link rel="stylesheet" type="text/css" href="./skins/css/button-alerts.css" />
    <style>
        /* Custom CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>License Activation</h1>
        <?php
        if (isset($_SESSION['success'])) {
        ?>
            <div class="alert alert-success">
                <?php echo $app->gets('success'); ?>
            </div>
        <?php
        }

        if (isset($_SESSION['error'])) {
        ?>
            <div class="alert alert-danger">
                <?php echo $app->gets('error'); ?>
            </div>
        <?php
        }
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="license-key">License Key:</label>
                <input type="text" id="license-key" name="license_key" placeholder="Enter your license key" required>
            </div>
            <div class="form-group">
                <label for="email">Domain:</label>
                <input type="text" id="email" name="license_domain" placeholder="Enter your domain url" required>
            </div>
            <button type="submit" name="submit">Activate License</button>
        </form>
    </div>
</body>

</html>