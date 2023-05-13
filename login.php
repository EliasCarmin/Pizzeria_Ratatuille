<?php

include './BD/conexion.php';

    session_start();
    $bloqueo_tiempo=60;
    /* Usuario 
    if(isset($_POST['login'])){

        $correo = $_POST['correo'];
        $correo = filter_var($correo, FILTER_SANITIZE_STRING);
        $contra_encrip = sha1($_POST['contra_encrip']);
        $contra_encrip = filter_var($contra_encrip, FILTER_SANITIZE_STRING);

        $select_usuario = $conn->prepare("SELECT * FROM `usuario` WHERE correo = ? AND contraseña = ?");
        $select_usuario->execute([$correo, $contra_encrip]);
        $row = $select_usuario->fetch(PDO::FETCH_ASSOC);

        if($select_usuario->rowCount() > 0){
            $_SESSION['usuario_id'] = $row['id'];
            header('location:./index.php');
        }
    }*/

    /* Admin */
    if(isset($_POST['login'])){

        $usuario = $_POST['correo'];
        $contraseña = $_POST['contra_encrip'];
    
        $conexion=mysqli_connect("localhost","root","","r_user");
        $consulta= "SELECT * FROM `user` WHERE user.nombre = '$usuario' AND user.password = '$contraseña'";
        $resultado=mysqli_query($conexion, $consulta);
        $filas=mysqli_fetch_array($resultado);
        

        
            if(isset($_COOKIE["block".$usuario])){
                $mensaje[] ="Usuario $usuario está bloqueado por 1 minuto";
            }else{
    
            if($filas && $filas['rol'] == 1 && $filas['cuenta'] == 1){ //admin
                $_SESSION['admin_id'] = $filas['id'];
                //numero de ingresos
                $consulta2="UPDATE user SET user.NumIngresos=user.NumIngresos+1 where user.nombre='$usuario' AND user.password='$contraseña'";
                mysqli_query($conexion, $consulta2);
                //ultima fecha de ingreso
                $consulta3="UPDATE user set Fec_Ultimo_Acceso=CURDATE() where user.nombre='$usuario' AND user.password='$contraseña'";
                mysqli_query($conexion, $consulta3);
                //ultima hora de ingreso
                $consulta4="UPDATE user set Hora_Ultimo_Acceso=CURTIME() where user.nombre='$usuario' AND user.password='$contraseña'";
                mysqli_query($conexion, $consulta4);
                header('location:./back/admin_index.php');
                
        
            }else if($filas['rol'] == 2 && $filas['cuenta'] == 1){//lector
                $_SESSION['usuario_id'] = $filas['id'];
                //numero de ingresos
                $consulta2="UPDATE user SET user.NumIngresos=user.NumIngresos+1 where user.nombre='$usuario' AND user.password='$contraseña'";
                mysqli_query($conexion, $consulta2);
                //ultima fecha de ingreso
                $consulta3="UPDATE user set Fec_Ultimo_Acceso=CURDATE() where user.nombre='$usuario' AND user.password='$contraseña'";
                mysqli_query($conexion, $consulta3);
                //ultima hora de ingreso
                $consulta4="UPDATE user set Hora_Ultimo_Acceso=CURTIME() where user.nombre='$usuario' AND user.password='$contraseña'";
                mysqli_query($conexion, $consulta4);
    
                header('Location:./upd_perfil.php');
            }else{
    
            //BLOQUEO DE USUARIO   
    
                if(isset($_COOKIE["$usuario"])){
                    $cont=$_COOKIE["$usuario"];
                    $cont++;
                    setcookie($usuario,$cont,time()+30);
                    if($cont>3){
                        setcookie("block".$usuario,$cont,time()+30);
                    }
                }else{
                    setcookie($usuario,1,time()+120);
                    //$mensaje[] = 'Usuario o Contraseña Incorrecto!';
                }
                
                
            }
        
        }

}   
    /*CODIGO DE GUÍA*/
    /*function acceso_user() {
        $nombre=$_POST['nombre'];
        $password=$_POST['password'];
        
        session_start();
        $_SESSION['nombre']=$nombre;
    
        $conexion=mysqli_connect("localhost","root","","r_user");
        $consulta= "SELECT * FROM user WHERE nombre='$nombre' AND password='$password'";
        $resultado=mysqli_query($conexion, $consulta);
        $filas=mysqli_fetch_array($resultado);
    
    
        if($filas['rol'] == 1 && $filas['cuenta'] == 1){ //admin
            //numero de ingresos
            $consulta2="UPDATE user SET user.NumIngresos=user.NumIngresos+1 where user.nombre='$nombre' AND user.password='$password'";
            mysqli_query($conexion, $consulta2);
            //ultima fecha de ingreso
            $consulta3="UPDATE user set Fec_Ultimo_Acceso=CURDATE() where user.nombre='$nombre' AND user.password='$password'";
            mysqli_query($conexion, $consulta3);
            //ultima hora de ingreso
            $consulta4="UPDATE user set Hora_Ultimo_Acceso=CURTIME() where user.nombre='$nombre' AND user.password='$password'";
            mysqli_query($conexion, $consulta4);
            header('Location: ../views/user.php');
            
    
        }else if($filas['rol'] == 2 && $filas['cuenta'] == 1){//lector
            header('Location: ../views/lector.php');
        }
        
        
        else{
    
            header('Location: login.php');
            session_destroy();
    
        }*/
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Inicio de Sesión</title> 
	<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" >


	<link rel="stylesheet" href="./css/login_registro.css">

    <link rel="icon" href="./img/logo_icons.png">
	

</head>  
<body>

    <?php
        if(isset($mensaje)){
            foreach($mensaje as $mensaje){
                echo '
                <div class="mensaje">
                    <span>'.$mensaje.'</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
                ';
            }
        }
    ?>

    <div class="container_login">  

        <form class="formulario_sesion" action="" method="POST">
        
            <h1 class="titulo">INICIO DE SESIÓN</h1>
            
            <div class="container">
            
            <div class="input-container">
                <i class="fas fa-user icon"></i>
                <input type="text" placeholder="ejemplo@gmail.com" required name="correo">
            </div>
                
            <div class="input-container">
                <i class="fas fa-key icon"></i>
                <input type="password" placeholder="Contraseña" required name="contra_encrip">
            </div>

            <div class="flex-btn">
                <input type="submit" value="Ingresar" class="btn" name="login">
                <a href="./index.php" class="btn-salir">Cancelar</a>
            </div>
            <p>¿No tienes una cuenta? <a class="link" href="./registrar.php">Registrate aquí</a></p>

            </div>

        </form>

    </div>  


    <script src="./js/index.js"></script>
  
</body>
</html>