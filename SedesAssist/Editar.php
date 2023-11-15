<?php 
require_once('BD/conexion.php');
require_once('Alumno/Alta.php');
require_once('Alumno/Baja.php');
require_once('Alumno/Modificar.php');

$orden = "apellido"; // Nombre de la columna por la que deseas ordenar
$direccion = "ASC"; // Dirección de la ordenación (puede ser "ASC" o "DESC")

$query = "SELECT * FROM alumnos
          ORDER BY $orden $direccion";

$STMT = $conn->prepare($query);
$STMT->execute();
$alumnos = $STMT->fetchAll(PDO::FETCH_ASSOC);

$modificar = new Modificar($conn);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="js/SweetAlert.js"></script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fondo.css">
    <title>Editar</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
            <a class="nav-link" href="IniciarSesion.php"> <img src="imagen\box-arrow-left.svg" ></a>
                <a class="nav-link" aria-current="page" href="Alumno.php">Alumno</a>
                <a class="nav-link" href="Profesor.php">Profesor</a>
                <a class="nav-link" href="Asistencia.php">Asistencias</a>
            </div>
        </div>
    </div>
</nav>
<div class="container my-3">
    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">  
            <?php            
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
            // Obtener los datos del formulario y almacenarlos en un arreglo
            $idAlumno=$_POST['idAlumno'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $fechaNacimiento = $_POST['fechaNacimiento'];
            $dni = $_POST['dni'];
           ?>
            <h3>EDITAR ALUMNO</h3>
            <form action="Editar.php" method="POST">
                <input type="hidden" name="idAlumno" value="<?php echo($idAlumno);?>">
                <label for="form-label">Nombre</label>
                <input type="text" class="form-control my-2" name="nombre" value="<?php echo($nombre);?>">

                <label for="form-label">Apellido</label>
                <input type="text" class="form-control my-2 "name="apellido" value="<?php echo($apellido);?>">

                <label for="form-label">Fecha de nacimineto</label>
                <input type="date" class="form-control my-2" name="fechaNacimiento" value="<?php echo($fechaNacimiento); ?>">

                <label for="form-label">DNI</label>
                <input type="text" class="form-control my-2" name="dni" value="<?php echo($dni); ?>">
                
                    <button type="submit" class="btn btn-success" name="guardar">GUARDAR</button>
                    <button type="submit" class="btn btn-danger" name="cancelar">CANCELAR</button>
            </form>  
            <?php
            }
            ?>
     <?php       
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar"])) {
        $alumno = array(
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'fechaNacimiento' => $_POST['fechaNacimiento'],
            'dni' => $_POST['dni'],
            'idAlumno' => $_POST['idAlumno']
        );
    
        try {
            
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

            $maxLongitudDni = 8;
            $maxLongitudNombre = 25;
            $maxLongitudApellido = 25;

            if (strlen($alumno["dni"]) <= $maxLongitudDni && strlen($alumno["nombre"]) <= $maxLongitudNombre && strlen($alumno["apellido"]) <= $maxLongitudApellido) {
                if (preg_match('/^[0-9]+$/', $alumno["dni"])) { 
                    if (preg_match('/^[A-Za-z\s]+$/', $alumno["nombre"])) {
                        if (preg_match('/^[A-Za-z ]+$/', $alumno["apellido"])){
                            $resultado = $modificar->modificar($alumno);
            
                            if ($resultado == true) {
                                echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "Alumno editado con éxito"
                                }).then(function() {
                                    window.location = "Alumno.php";
                                });
                                </script>';
                            } else {
                                echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Los datos ingresados son erroneos o estan sin completar",
                                    text: "Por favor verifique los datos ingresados",
                                }).then(function() {
                                    window.location.href = "Alumno.php";
                                });
                                </script>';
                                exit;
                            }
                        } else {
                            echo '<script>
                                Swal.fire({
                                    icon: "info",
                                    title: "Verifique el apellido ingresado",
                                    text: "No se pueden poner numeros en los nombres y apellidos",
                                }).then(function() {
                                    window.location = "Alumno.php";
                                });
                                </script>';
                        }
                        
                    } else {
                        echo '<script>
                                Swal.fire({
                                    icon: "info",
                                    title: "Verifique el nombre ingresado",
                                    text: "No se pueden poner numeros en los nombres y apellidos",
                                }).then(function() {
                                    window.location = "Alumno.php";
                                });
                                </script>';
                    }
                } else {
                    echo '<script>
                                Swal.fire({
                                    icon: "info",
                                    title: "Verifique el DNI ingresado",
                                    text: "No se pueden ingresar letras en el DNI",
                                }).then(function() {
                                    window.location = "Alumno.php";
                                });
                                </script>';
                }
            } else {
                echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Los caracteres son demasiado largos para ser editados"
                                }).then(function() {
                                    window.location = "Alumno.php";
                                });
                                </script>';
            }
            
            foreach ($alumnos as $alumnoo) {
                if ($alumnoo["dni"] == $alumno["dni"] && $alumno["idAlumno"]<> $alumnoo["idAlumno"] ) {
                    echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "El DNI ya existe en la base de datos",
                        text: "DNI no ingresado",
                    }).then(function() {
                        window.location = "Alumno.php";
                    });
                    </script>';
                    exit; 
                }
            }
            
            
        } catch (PDOException $e) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión a la base de datos',
                    text: 'Por favor, inténtelo de nuevo más tarde',
                }).then(function() {
                    window.location = 'Alumno.php';
                });
            </script>";
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancelar"])) {
        header("Location: Alumno.php");
        exit;
    }

?>
</body>
</html>