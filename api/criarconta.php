<?php
require_once('helpers.php');
require_once('connect.php');

require '../vendor/autoload.php';
use Mailgun\Mailgun;

$mgClient = new Mailgun(MAILGUN_KEY);
$domain = MAILGUN_DOMAIN;

header('Access-Control-Allow-Origin: *'); 


$table            = 'users';

$name             = $_POST['name'];
$email            = $_POST['email'];
$user             = $_POST['user'];
$password         = $_POST['password'];
$password_confirm = $_POST['confirmPassword'];

//Verifica se existe uma conexão com o banco de dados
if (!isset($conn) || !is_object($conn)):
	exit('Erro na conexão com o banco de dados.');
endif;

//Verifica se todos os campos foraam preenchidos
foreach($_POST as $post):
    if(empty($post)):
        echo json_encode(['success' => 0, 'msg' => 'Os campos marcados com (*) são de preenchimento obrigatório.']);
        return false;
    endif;
endforeach;

//Valida o formato do e-mail
if(!validateEmail($email)):
   echo json_encode(['success' => 0, 'msg' => 'E-mail inválido']);
   return false;
endif;

//Verifica se o e-mail já está cadastrado
$validateEmailUnique = $conn->prepare('SELECT * FROM '.$table.' WHERE email=:email');
$validateEmailUnique->execute(['email' => $email]);

if(count($validateEmailUnique->fetchAll())):
   echo json_encode(['success' => 0, 'msg' => 'E-mail já cadastrado']);
   return false;
endif;

//Verific se o nome de usuário esta disponível 
$validateUserUnique = $conn->prepare('SELECT * FROM '.$table.' WHERE user=:user');
$validateUserUnique->execute(['user' => $user]);

if(count($validateUserUnique->fetchAll())):
   echo json_encode(['success' => 0, 'msg' => 'Usuário indisponível']);
   return false;
endif;

//Confirma se a senha foi confirmada
if($password != $password_confirm):
    echo json_encode(['success' => 0, 'msg' => 'Senhas não conferem']);
    return false;
endif;

//Salva o usuário no banco de dados
$sql = "INSERT INTO ".$table." (name, email, user, token, password, status) VALUES (:name, :email, :user, :token, :password, :status)"; 

$save = $conn->prepare($sql);
$save->execute([
    'name'     => $name,
    'email'    => $email,
    'user'     => $user,
    'token'    => md5(uniqid(rand(), true)),
    'password' => md5($password),
    'status'   => 0,
]);

//Pega o id do ultimo registro
$newId = $conn->lastInsertId();

//Consulta do usuário que foi cadastrado
$getRegister = $conn->prepare('SELECT * FROM '.$table.' WHERE id=:id');
$getRegister->execute(['id' => $newId]);

//Pegas as informações do usuário
$register = $getRegister->fetch();

 
//Envia o e-mail de confirmação
$send = $mgClient->sendMessage($domain, array(
    'from'    => 'MyTest <contato@williamewerton.com.br>',
    'to'      => $register['name'].'<'.$register['email'].'>',
    'subject' => 'Ativação de conta | MyTest',
    'html'    => 
    '<html>
        <h2>Olá, '.$register['name'].', acesse o link abaixo para ativar sua conta no MyTest</h2>
        <a href="'.APP_URL.'/api/ativacao.php?t='.$register['token'].'" target="_blank"> Clique aqui</a>
        <p>Ou copie o link '.APP_URL.'/api/ativacao.php?t='.$register['token'].' e cole no seu navegador</p>
    </html>'
));

echo json_encode(['success' => 1, 'msg' => 'A sua conta foi criada com sucesso! Um link de ativação foi enviado ao e-mail '.$register['email']]);

//Fecha conexão com banco de dados
$conn = null;
