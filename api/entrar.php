<?php
require_once('connect.php');
require '../vendor/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

header('Access-Control-Allow-Origin: *'); 


//Verifica se existe uma conexão com o banco de dados
if (!isset($conn) || !is_object($conn)):
	exit('Erro na conexão com o banco de dados.');
    return false;
endif;

//Verifica se os campos foram preenchidos
if(empty($_POST['user']) && empty($_POST['password'])):
    echo json_encode(['success' => 0, 'msg' => 'Preencha os campos corretamente', 'alert' => 'alert-danger']);
    return false;
endif;

$user = $_POST['user'];
$password = $_POST['password'];

//Checar usuário no banco de dados
$check_user = $conn->prepare('SELECT * FROM users WHERE user = :user OR email = :user LIMIT 1');
$check_user->execute(['user' => $user]);

//Verifica se o usuário existe
if($check_user->rowCount() == 0):
    echo json_encode(['success' => 0, 'msg' => 'E-mail / Usuário incorreto', 'alert' => 'alert-danger']);
    return false;
endif;

//Pega as informações do usuário
$getUser = $check_user->fetch();
$iduser = $getUser['id'];

//Verifica se a conta está ativada
if($getUser['status'] != 1):
    $_SESSION['auth'] = false;
    echo json_encode([
        'success' => 0, 
        'msg' => 'A sua conta ainda não foi ativada! Se não encontrou o e-mail olhe na caixa de spam.', 
        'alert' => 'alert-warning'
        ]);
    return false;
endif;

//Verifica se a senha está correta
if(md5($password) === $getUser['password']):
    
    $signer = new Sha256();

    $token = (new Builder())->setIssuer('http://mytest.com') // Configures the issuer (iss claim)
                        ->setAudience('http://mytest.org') // Configures the audience (aud claim)
                        ->setId(md5(time()).$iduser, true) // Configures the id (jti claim), replicating as a header item
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                        ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                        ->set('success', 1)
                        ->set('userName', $getUser['name'])
                        ->set('userEmail', $getUser['email'])
                        ->set('userUser', $getUser['user'])
                        ->set('userId', $iduser) 
                        ->sign($signer, 'mytest') 
                        ->getToken(); // Retrieves the generated token
                        
    
    //echo $token;
    header('token:'.$token);
    echo $token;//json_encode(['success' => 1]);
else:    
    echo json_encode(['success' => 0, 'msg' => 'Senha incorreta', 'alert' => 'alert-danger']);
    return false;
endif;


