<?php

// $dns = "mysql:host=127.0.0.1;dbname=lockerbookingsystemdb"; 
// $user= "root"; // root is default value until you change it 
// $pass= "Ab*015*200#";

$serverName = getenv('localhost');
$userName = getenv('root');
$password = getenv('Ab*015*200#');
$dbName = getenv('lockerbookingsystemdb');


// Now we will check if the connection is ok or no

try{
    // trying the connection
    $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){ 
    // catch for if there is a problem with server, the PDO solve it and handling the error
    echo "Failed To Connect With Data Base" . $e->getMessage(); 
}

?>
