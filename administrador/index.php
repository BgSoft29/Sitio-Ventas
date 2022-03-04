<?php
session_start();
    if($_POST){
        if(($_POST['usuario']=='brunobgoxa') && ($_POST['contrasenia']=="sistema")){
            $_SESSION['usuario']= 'ok';
            $_SESSION['nombreUsuario'] = $_POST['usuario'];
            header("Location: inicio.php");
        } else {
            $mensaje = "El usuario o contraseña son incorrectos";
        }

    }
?>
<!doctype html>
<html lang="es">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <div class="row" style="width: 100%; display: flex; justify-content: center;">
            <div class="col-md-4">
                <br/>   <br/>
                
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <?php if(isset($mensaje)){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                        <?php } ?>
                        <form method="POST" > 
                            <!-- !crt para crear formulario -->
                            <div class = "form-group">
                                <label for="exampleInputEmail1">Usuario:</label>
                                <input type="text" class="form-control" placeholder="Ingrese su nombre de usuario" name="usuario" >
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Contraseña:</label>
                                <input type="password" class="form-control" placeholder="Ingrese su contraseña de administrador" name="contrasenia" >
                            </div>
                            <button type="submit" class="btn btn-primary">Entrar al administrador</button>
                        </form>   
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
  </body>
</html>