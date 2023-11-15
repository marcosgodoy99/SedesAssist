<?php

require_once('BD/ConectarBD.php');

$conexion = conectarBaseDeDatos();

// Obtén la información del profesor desde la base de datos
$query = "SELECT * FROM profesores";
$STMT = $conexion->prepare($query);
$STMT->execute();
$profesores = $STMT->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["iniciar"])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];

    foreach ($profesores as $profesor) {
        if ($nombre == $profesor['nombre'] && $apellido == $profesor['apellido']) {
            // Guarda la información del profesor en la sesión
            session_start();
            $_SESSION['idProfesor'] = $profesor['idProfesor'];

            // Redirige a Asistencia.php
            header("location: Asistencia.php");
            exit(); // Asegúrate de salir después de redirigir
        }
    }
    // Si no se encuentra un profesor válido, puedes manejarlo aquí (por ejemplo, mostrar un mensaje de error).
    echo "Error: Profesor no encontrado";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

 <link rel="stylesheet" href="login/style.css">
  <title>LOGIN</title>

</head>
<body>
  <div class="fondito">
    <form action="IniciarSesion.php" method="POST">
      <h1>Iniciar Sesión</h1>
      <div class="input-box">
          <input type="text" name="nombre" placeholder="Nombre...">
      </div>
      <div class="input-box">
          <input type="text" name="apellido" placeholder="Apellido...">
      </div>
      
      <button type="submit" class="btn" name="iniciar">Iniciar Sesión</button>
      
      <form action="">
      <div class="register">
          <p>¿No tienes una cuenta?<a href="registrarprofe.php"> Crea un perfil nuevo.</a></p>
      </div>
      </form>

    </form>
</div>

</body>
</html>


