<!DOCTYPE>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>MyTest</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="content-ativacao">
                        <?php                             
                        if(isset($_GET['t'])):
                            require_once('connect.php');
                            $getUser = $conn->prepare('SELECT * FROM users WHERE token=:token');
                            $getUser->execute(['token' => $_GET['t']]);
                            $user = $getUser->fetch();
                        

                        if($user && $user['status'] != 1):
                            $active = $conn->prepare('UPDATE users SET status=:status WHERE id=:id');
                            $active->execute(['status' => 1, 'id' => $user['id']]);                            
                        
                        ?>
                            <span class="glyphicon glyphicon-check green"></span>
                            <h2>Parabéns <?php echo $user['name']; ?>, sua conta foi ativada com sucesso!</h2>
                            <a href="<?php echo APP_URL; ?>" class="btn btn-lg btn-primary">Entrar</a>
                        <?php else: ?>
                            <span class="glyphicon glyphicon-remove red"></span>
                            <h2>Link inválido!</h2>
                        <?php                        
                            endif;
                        else:
                            header("Location: 404.html");
                        endif;
                        
                        ?>
                    </div> 
                </div>
            </div> 
        </div>
    </body>
</html>