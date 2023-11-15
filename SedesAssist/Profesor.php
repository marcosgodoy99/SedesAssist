<?php 
require_once('BD/conexion.php');
require_once('Profesor/Alta.php');
require_once('Profesor/Baja.php');
require_once('Profesor/Modificar.php');

$baja = new Baja($conn);


$query = "SELECT * FROM profesores";
$STMT = $conn->prepare($query);
$STMT->execute();
$profesor = $STMT->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>
    <head>
    <title>Profesores</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fondo.css">
    <script src="js/SweetAlert.js"></script>
    <script src="js/bootstrap.js"></script>
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
                <a class="nav-link" href="Alumno.php">Alumno</a>
                <a class="nav-link active" href="Profesor.php">Profesor</a>
                <a class="nav-link" href="Asistencia.php">Asistencias</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <form method="POST" action="Profesor.php">
                <h3>REGISTRAR PROFESOR</h3>
                <input type="text" class="form-control my-2" name="dni" placeholder="DNI">
                <input type="text" class="form-control my-2" name="nombre" placeholder="Nombre">
                <input type="text" class="form-control my-2" name="apellido" placeholder="Apellido">
                <input type="date" class="form-control my-2" name="fechaNacimiento">
                <button type="submit" class="btn btn-primary my-3" name="registrar">Registrar</button>
            </form>
          
        </div>
        <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <table class="table table-dark">
                <tr>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($profesor as $profe) { ?>
                <tr>
                    <td><?php echo $profe['apellido']; ?></td>
                    <td><?php echo $profe['nombre']; ?></td>
                    <td><?php echo $profe['dni']; ?></td>
                    <td><?php echo $profe['fechaNacimiento']; ?></td>
                    <td class="d-flex">
                        <form method="POST" action="Profesor/modificar.php">
                            <input type="hidden" name="idAlumno" value="<?php echo $profe['idProfesor']; ?>">
                            <input type="hidden" name="nombre" value="<?php echo $profe['nombre']; ?>">
                            <input type="hidden" name="apellido" value="<?php echo $profe['apellido']; ?>">
                            <input type="hidden" name="dni" value="<?php echo $profe['dni']; ?>">
                            <input type="hidden" name="fechaNacimiento" value="<?php echo $profe['fechaNacimiento']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm mx-1" name="editar"><img src="imagen/edit.svg" alt=""></button>
                        </form>
                        <form method="POST" action="Profesor.php">
                            <input type="hidden" name="profesor_id" value="<?php echo $profe['idProfesor']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="eliminar"><img src="imagen/trash-2.svg" alt=""></button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST["eliminar"])) {
    
    $profesor_id = $_POST["profesor_id"];
    $baja->delete($profesor_id);
    echo '<script>
    Swal.fire({
        icon: "error",
        title: "Profesor eliminado con exito"
    }).then(function() {
        window.location = "Profesor.php";
    });
</script>';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["registrar"])) {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    
    $originalErrorMode = $conn->getAttribute(PDO::ATTR_ERRMODE);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

    foreach ($profesor as $profe) {
        if ($dni==$profe['dni']) {
            echo '<script>Swal.fire({
                icon: "error",
                title: "El DNI ya existe en la base de datos",
                text: "Por favor ingrese otro DNI",
            }).then(function() {
                window.location = "Profesor.php";
            });</script>';
             $conn->setAttribute(PDO::ATTR_ERRMODE, $originalErrorMode);

        exit();
        }
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, $originalErrorMode);

    $fechaN = new DateTime($fechaNacimiento);
    $fechaActual = new DateTime();
    $edad = $fechaActual->diff($fechaN)->y;

if ($edad >= 17 && $edad <=90) {
        $maxLongitudDni = 8;
        $maxLongitudNombre = 25;
        $maxLongitudApellido = 25;

    if (strlen($dni) <= $maxLongitudDni && strlen($nombre) <= $maxLongitudNombre && strlen($apellido) <= $maxLongitudApellido) {
        if (preg_match('/^[0-9]+$/', $dni)) { 
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
                            window.location = "Profesor.php";
                        });
                    </script>';
                } else {
                    echo '<script>
                    Swal.fire({
                        icon: "info",
                        title: "Verifique el apellido ingresado",
                        text: "No se pueden poner numeros en los nombres y apellidos",
                    }).then(function() {
                        window.location = "Profesor.php";
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
                    window.location = "Profesor.php";
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
                         window.location = "Profesor.php";
                 });</script>';
        }
    } else {
        echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "Los caracteres son demasiado largos para ser registrados"
                }).then(function() {
                    window.location = "Profesor.php";
                });</script>';
    }
} else {
    echo '<script>
    Swal.fire({
        icon: "info",
        title: "Ingrese bien la fecha",
        text: "El profesor debe ser mayor de edad o la fecha ingresada no es valida"
    }).then(function() {
        window.location = "Profesor.php";
    });
    </script>';
}

}
?>
</body>
</html>