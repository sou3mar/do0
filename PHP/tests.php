<?php
require_once 'class.php';

$class = new Do0('API KEY'); // API key passed to the class
echo $class->buildURL()->getApiURL() .'<br>'; // Builds API url and prints it
echo 'API version: '. $class->getApiVersion() .'<br>';

try {
    $handler = $class->shorten('URL TO BE SHORTENED');
    var_dump( $handler );
} catch(Exception $e){
    echo $e->show();
}

?>