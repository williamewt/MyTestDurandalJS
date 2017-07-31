<?php

if(!isset($_POST['id'])):
    echo json_encode(['success' => 1, 'alert' => 'alert-danger', 'msg' => 'Usuário inexistente']);
    return false;
endif;

$userId = $_POST['id'];
$table  = 'users';

require_once('connect.php');

require '../vendor/autoload.php';
use Mailgun\Mailgun;

$mgClient = new Mailgun(MAILGUN_KEY);
$domain = MAILGUN_DOMAIN;

//Consulta do usuário que foi cadastrado
$getRegister = $conn->prepare('SELECT * FROM '.$table.' WHERE id=:id LIMIT 1');
$getRegister->execute(['id' => $userId]);

//Pegas as informações do usuário
$register = $getRegister->fetch();

 
//Envia o e-mail de confirmação
$send = $mgClient->sendMessage($domain, array(
    'from'    => 'MyTest <contato@williamewerton.com.br>',
    'to'      => $register['email'],
    'subject' => 'Ativação de conta | MyTest',
    'html'    => 
    '<html>
        <h2>Olá, '.$register['name'].', acesse o link abaixo para ativar sua conta no MyTest</h2>
        <a href="'.APP_URL.'/ativacao?t='.$register['token'].'" target="_blank"> Clique aqui</a>
        <p>Ou copie o link '.APP_URL.'/ativacao?t='.$register['token'].' e cole no seu navegador</p>
    </html>'
));

if($send):
    echo json_encode(['success' => 1,'alert' => 'alert-success', 'msg' => 'Um link de ativação foi enviado ao e-mail '.$register['email']]);
endif;