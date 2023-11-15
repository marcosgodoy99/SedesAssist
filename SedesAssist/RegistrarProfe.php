<?php 
require_once('Profesor/Alta.php');
require_once('BD/conexion.php');

$query = "SELECT * FROM profesores";
$STMT = $conn->prepare($query);
$STMT->execute();
$profesor = $STMT->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="login/style.css">
    <script src="js/SweetAlert2.js"></script>
    <title>registrar profesor</title>
</head>
<body>
 
<div class="fondito">
    <div class="container my-3">
        <form method="POST" action="registrarprofe.php">
            <h3>REGISTRAR PROFESOR</h3>
            <div class="input-box">
            <input type="text"  name="dni" placeholder="DNI">
            <input type="text"  name="nombre" placeholder="Nombre">
            <input type="text"  name="apellido" placeholder="Apellido">
            <input type="date"  name="fechaNacimiento">
            </div>

            <div class="btn">
            <button type="submit" class="btn btn-primary my-3" name="registrar">Registrar</button>
            </div>
        </form>
    </div>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["registrar"])) {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechaNacimiento = $_POST['fechaNacimiento'];

    foreach ($profesor as $profe) {
        if ($dni==$profe['dni']) {
            echo '<script>Swal.fire({
                icon: "error",
                title: "El DNI ya existe en la base de datos",
                text: "Por favor ingrese otro DNI",
            }).then(function() {
                window.location = "registrarprofe.php";
            });</script>';
        }
    }
    
    $fechaN = new DateTime($fechaNacimiento);
    $fechaActual = new DateTime();
    $edad = $fechaActual->diff($fechaN)->y;

if ($edad >= 17) {
        $maxLongitudDni = 8;
        $maxLongitudNombre = 25;
        $maxLongitudApellido = 25;

    if (strlen($dni) <= $maxLongitudDni && strlen($nombre) <= $maxLongitudNombre && strlen($apellido) <= $maxLongitudApellido) {
        if (preg_match('/^[0-8]+$/', $dni)) { 
            if (preg_match('/^[A-Za-z]+$/', $nombre)) {
                if (preg_match('/^[A-Za-z]+$/', $apellido)) {
                    $nuevoProfesor = [
                        'dni' => $dni,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'fechaNacimiento' => $fechaNacimiento,
                    ];
                    $alta = new Alta($nuevoProfesor, $conn);
                echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Registro exitoso",
                            text: "El profesor se ha registrado correctamente",
                        }).then(function() {
                            window.location = "IniciarSesion.php";
                        });
                    </script>';
                } else {
                    echo '<script>Swal.fire("Verifica el apellido ingresado");</script>';
                }
                
            } else {
                echo '<script>Swal.fire("Verifica el nombre ingresado");</script>';
            }
        } else {
            echo '<script>Swal.fire("Verifica el DNI ingresado");</script>';
        }
    } else {
        echo '<script>Swal.fire("Los datos ingresados son demasiado largos");</script>';
    }
} else {
    echo '<script>Swal.fire("El Profesor debe ser mayor de 17 años");</script>';
}
}
?>
</body>
</html>