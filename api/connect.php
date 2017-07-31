<?php
require_once('../config.php');



//Tenta conectar ao banco de dados
try {

    $conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    echo 'ERROR: ' . $e->getMessage();
    
    die();
}