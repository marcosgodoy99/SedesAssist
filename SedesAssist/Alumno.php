<?php 
require_once('BD/conexion.php');
require_once('Alumno/Alta.php');
require_once('Alumno/Baja.php');
require_once('Alumno/Modificar.php');

$baja = new Baja($conn);


$orden = "apellido"; // Nombre de la columna por la que deseas ordenar
$direccion = "ASC"; // Dirección de la ordenación (puede ser "ASC" o "DESC")

$query = "SELECT * FROM alumnos
          ORDER BY $orden $direccion";

$STMT = $conn->prepare($query);
$STMT->execute();
$alumnos = $STMT->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Alumnos</title>
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
                <a class="nav-link active"  href="Alumno.php">Alumno</a>
                <a class="nav-link" href="Profesor.php">Profesor</a>
                <a class="nav-link" href="Asistencia.php">Asistencias</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <form method="POST" action="Alumno.php">
                <blockquote class="blockquote">
                    <p>REGISTRAR ALUMNO</p>
                    </blockquote>
                <input type="text" class="form-control my-2" name="dni" placeholder="DNI">
                <input type="text" class="form-control my-2" name="nombre" placeholder="Nombre">
                <input type="text" class="form-control my-2" name="apellido" placeholder="Apellido">
                <input type="date" class="form-control my-2" name="fechaNacimiento">
                <button type="submit" class="btn btn-primary my-3" name="registrar">Registrar</button>
            </form>
            <form method="POST" action="Buscar.php">
                <p>BUSCAR ALUMNO</p>
                <input type="text" class="form-control my-2"name="busqueda" placeholder="Buscar...">
                <button type="submit" class="btn btn-primary">Buscar</button>
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
                <?php foreach ($alumnos as $alumno) { ?>
                <tr>
                    <td><?php echo $alumno['apellido']; ?></td>
                    <td><?php echo $alumno['nombre']; ?></td>
                    <td><?php echo $alumno['dni']; ?></td>
                    <td><?php echo $alumno['fechaNacimiento']; ?></td>
                    <td class="d-flex">
                        <form method="POST" action="Editar.php">
                            <input type="hidden" name="idAlumno" value="<?php echo $alumno['idAlumno']; ?>">
                            <input type="hidden" name="nombre" value="<?php echo $alumno['nombre']; ?>">
                            <input type="hidden" name="apellido" value="<?php echo $alumno['apellido']; ?>">
                            <input type="hidden" name="dni" value="<?php echo $alumno['dni']; ?>">
                            <input type="hidden" name="fechaNacimiento" value="<?php echo $alumno['fechaNacimiento']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm mx-1" name="editar"><img src="imagen/edit.svg" alt=""></button>
                        </form>
                        <form method="POST" action="Alumno.php">
                            <input type="hidden" name="alumno_id" value="<?php echo $alumno['idAlumno']; ?>">
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
    
    $alumno_id = $_POST["alumno_id"];
    $baja->eliminar($alumno_id);
    echo '<script>
    Swal.fire({
        icon: "error",
        title: "Alumno eliminado con exito"
    }).then(function() {
        window.location = "Alumno.php";
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

    foreach ($alumnos as $alumno) {
        if ($dni==$alumno['dni']) {
            echo '<script>Swal.fire({
                icon: "error",
                title: "El DNI ya existe en la base de datos",
                text: "Por favor ingrese otro DNI",
            }).then(function() {
                window.location = "Alumno.php";
            });</script>';
            $conn->setAttribute(PDO::ATTR_ERRMODE, $originalErrorMode);

            exit();
        }
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, $originalErrorMode);

    $fechaN = new DateTime($fechaNacimiento);
    $fechaActual = new DateTime();
    $edad = $fechaActual->diff($fechaN)->y;

if ($edad >= 17 && $edad <= 60) {
        $maxLongitudDni = 8;
        $maxLongitudNombre = 25;
        $maxLongitudApellido = 25;

    if (strlen($dni) <= $maxLongitudDni && strlen($nombre) <= $maxLongitudNombre && strlen($apellido) <= $maxLongitudApellido) {
        if (preg_match('/^[0-9]+$/', $dni)) { 
            if (preg_match('/^[A-Za-z\s]+$/', $nombre)) {
                if (preg_match('/^[A-Za-z ]+$/', $apellido)){
                    $nuevoAlumno = [
                        'dni' => $dni,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'fechaNacimiento' => $fechaNacimiento,
                    ];
                    $alta = new Alta($nuevoAlumno, $conn);
                echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Registro exitoso",
                            text: "El alumno se ha registrado correctamente",
                        }).then(function() {
                            window.location = "Alumno.php";
                        });
                    </script>';
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
                 });</script>';
        }
    } else {
        echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "Los caracteres son demasiado largos para ser registrados"
                }).then(function() {
                    window.location = "Alumno.php";
                });</script>';
    }
} else {
    echo '<script>
    Swal.fire({
        icon: "info",
        title: "Ingrese bien la fecha",
        text: "El alumno debe ser mayor de edad o la fecha ingresada no es valida"
    }).then(function() {
        window.location = "Alumno.php";
    });
    </script>';
}
}
?>

</body>
</html>