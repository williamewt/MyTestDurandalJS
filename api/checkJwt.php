<?php

require '../vendor/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$signer = new Sha256();

header('Access-Control-Allow-Origin: *'); 

$token = $_POST['token'];

if($token->verify($signer, 'mytest')):
echo $token;
else:
    echo json_encode(['success' => 0, 'msg' => 'Dados inválidos', 'alert' => 'alert-danger']);
    return false;
endif;