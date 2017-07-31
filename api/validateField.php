<?php
require_once('connect.php');

$field      = $_POST['field'];
$fieldValue = $_POST['fieldValue'];
$table = 'users';


$validate = $conn->prepare('SELECT * FROM '.$table.' WHERE '.$field.' = :field');
$validate->execute(['field' => $fieldValue]);

$result = $validate->fetchAll();

if(count($result)):
    header('Access-Control-Allow-Origin: *'); 
    echo json_encode(['validate' => false]);
else:
    header('Access-Control-Allow-Origin: *'); 
    echo json_encode(['validate' => true]);
endif;