<?php
require_once "classes/shortener.php";

$class = new Do0('API KEY');

echo $class->buildURL()->getApiURL() .'<br>'; // Builds API URL according to the current version code
echo 'API version: '. $class->getApiVersion() .'<br>'; // Prints current API version code

// $class->versionForceUpdate();
// |-> this function will make a new version file

try {
    $url     = 'https://google.com'; // URL to be shortened
    $handler = $class->shorten( $url );
    var_dump( $handler );
} catch(Exception $e){
    echo $e->show();
}

?>