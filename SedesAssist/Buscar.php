<?php
require_once('BD/conexion.php');

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST["busqueda"])){

    $busqueda = $_POST['busqueda'];
    $busqueda = trim($busqueda);
    $busqueda = $busqueda . '%';
   

    $query = "SELECT * FROM alumnos WHERE apellido LIKE :busqueda ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':busqueda', $busqueda);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultados) > 0) {
        ?>
        <!DOCTYPE html>
<html>
<head>
    <title>busqueda</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fondo.css">
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
                <a class="nav-link " aria-current="page" href="Alumno.php">Alumno</a>
                <a class="nav-link" href="Profesor.php">Profesor</a>
                <a class="nav-link" href="Asistencia.php">Asistencias</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-3">
    <div class="row">
        <div class="col-12">
            <table class="table table-dark">
                <tr>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($resultados as $fila) { ?>
                <tr>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['dni']; ?></td>
                    <td><?php echo $fila['fechaNacimiento']; ?></td>
                    <td class="d-flex">
                        <form method="POST" action="Editar.php">
                            <input type="hidden" name="idAlumno" value="<?php echo $fila['idAlumno']; ?>">
                            <input type="hidden" name="nombre" value="<?php echo $fila['nombre']; ?>">
                            <input type="hidden" name="apellido" value="<?php echo $fila['apellido']; ?>">
                            <input type="hidden" name="dni" value="<?php echo $fila['dni']; ?>">
                            <input type="hidden" name="fechaNacimiento" value="<?php echo $fila['fechaNacimiento']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm mx-1" name="editar"><img src="imagen/edit.svg" alt=""></button>
                        </form>
                        <form method="POST" action="Alumno.php">
                            <input type="hidden" name="alumno_id" value="<?php echo $fila['idAlumno']; ?>">
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
       }

       else{
        ?>

 <!DOCTYPE html>
<html>
<head>
    <title>busqueda</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fondo.css">
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
                <a class="nav-link active" aria-current="page" href="Alumno.php">Alumnos</a>
                <a class="nav-link" href="Profesor.php">Profesores</a>
                <a class="nav-link" href="Asistencias.php">Asistencias</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-3">
    <div class="row">
        <div class="col-12">
            <table class="table table-dark">
                
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acciones</th>
                </tr>

                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?php echo('Alumnos no encontrados'); ?></th>
                    <th></th>
                </tr>
                    
                <?php } ?>
            </table>
        </div>
    </div>
</div>


        <?php
       }
?>
<script src="js/bootstrap.js"></script>
</body>
</html>