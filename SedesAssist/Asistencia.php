<?php 
require_once('BD/conexion.php');
require_once('Promediar.php');

$orden = "apellido";
$direccion = "ASC"; 
$query = "SELECT * FROM alumnos
          ORDER BY $orden $direccion";
$STMT = $conn->prepare($query);
$STMT->execute();
$alumnos = $STMT->fetchAll(); 
$boolean = 0;

$query = "SELECT * FROM parametros";
$STMT = $conn->prepare($query);
$STMT->execute();
$parametros = $STMT->fetchAll();

$query = "SELECT * FROM profesores";
$STMT = $conn->prepare($query);
$STMT->execute();
$profesor = $STMT->fetchAll(PDO::FETCH_ASSOC);

$clasesTotales = $parametros[0]['clasesTotales'];
$porcentajePromocion = $parametros[0]['porcentajePromocion'];
$porcentajeRegular = $parametros[0]['porcentajeRegular'];

session_start();
if (isset($_SESSION['idProfesor'])) {
    $idProfesor = $_SESSION['idProfesor'];
    // Ahora $idProfesor contiene el valor almacenado en la sesión
} else {
    // Si no está seteado, puede manejarlo de alguna manera
    // Por ejemplo, redirigir a la página de inicio de sesión.
    header('Location: IniciarSesion.php');
    exit();
}

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST["presente"])) {
    
        $idAlumno = $_POST["idAlumno"];
        $idProfesor;

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date('Y-m-d.H:i:s');
    
        $query = "INSERT INTO asistencias(idAlumno,fechaAsistencia,idProfesor) VALUES (:idAlumno,:fechaAsistencia,:idProfesor)";
        $STMT = $conn->prepare($query);
        $STMT->bindParam(":idAlumno", $idAlumno);
        $STMT->bindParam(":fechaAsistencia", $fecha);
        $STMT->bindParam(":idProfesor", $idProfesor);
        $STMT->execute();
        

    }

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST["eliminarPresente"])) {
    
        $idAlumno = $_POST["idAlumno"];
   

        $query = "DELETE FROM asistencias 
          WHERE idAlumno = $idAlumno 
          ORDER BY fechaAsistencia DESC 
          LIMIT 1";
        $STMT = $conn->prepare($query);
        $STMT->execute();
        header("location:Asistencia.php");
        exit();
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Interfaz</title>
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
                <a class="nav-link active" href="Asistencia.php">Asistencias</a>
            </div>
            <div class="position-absolute top-0 end-0"> 
                <button class="btn btn-link" id="btnAbrirAjustes"> <img src="imagen/gear.svg"></button>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="modalAjustes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajustes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label for="campo1">CANTIDAD TOTAL DE CLASES:</label>
                    <input type="text" id="campo1" placeholder='<?PHP echo $clasesTotales ?>' >
                </div>
                <div class="my-2">
                    <label for="campo2">PORCENTAJE PARA PROMOCION:</label>
                    <input type="text"  id="campo2" placeholder='<?PHP echo $porcentajePromocion ?>' >
                </div>
                <div>
                    <label for="campo3">PORCENTAJE PARA REGULARIZAR:</label>
                    <input type="text" id="campo3" placeholder='<?PHP echo $porcentajeRegular ?>'>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarAjustes" name="promediar" onclick="parametrosAjax()">Guardar cambios</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="container my-4">
    <form action="Asistencia.php" method="POST">
        <button type="submit" name="promediar" class="btn btn-primary btn-sm">Promediar</button>
        <?php 

        $promediador = new Promediar($conn);
        $resultados = $promediador->calcular();
        
        ?>
    </form>

</div>
<div class="container my-4">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-dark">
                <tr>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Asistencias</th>
                    <th>Promedio</th>
                    <th>Acciones</th>
                </tr>
                <?php 
                $i = 0;
                foreach ($alumnos as $alumno) { ?>
                <tr>
                    <td><?php echo $alumno['apellido']; ?></td>
                    <td><?php echo $alumno['nombre']; ?></td>
                    <td><?php echo $alumno['dni']; ?></td>
                    <td>
                    <?php 
                        $idAlumno = $alumno['idAlumno'];
                        $query = "SELECT COUNT(fechaAsistencia) FROM asistencias
                             WHERE idAlumno =  $idAlumno" ;
                         $STMT = $conn->prepare($query);
                        $STMT->execute();
                        $asistencia = $STMT->fetch();
                        echo $asistencia[0]; 
                    ?>
                    </td>
                    <td>

                    <?php
                    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST["promediar"])) {
    
                    echo $resultados[$i];
                    $i++;

                     };

                    ?>

                    </td>
                    <td class="d-flex">
                        <form method="POST" action="Asistencia.php">
                            <input type="hidden" name="idAlumno" value="<?php echo $alumno['idAlumno']; ?>">
                            <button type="button" class="btn btn-success btn-sm mx1" name="ajax-button" onclick="presenteAjax('<?php echo $alumno['idAlumno']; ?>' , '<?php echo $idProfesor; ?>')"> <img src="imagen/check.svg"> </button>
                            <button type="submit" class="btn btn-danger btn-sm mx-1" name="eliminarPresente"> <img src="imagen/x-circle.svg"> </button>
                        </form>
                    </td>     
                </tr>
                <?php }
                    if($boolean == 1){
                     echo '<script language="javascript">alert("Ingrese un valor mayor a 0 o menor a 190");</script>';
                    }; 
                ?>
                    
            </table>
        </div>
    </div>
</div>


<script src="js/bootstrap.js"></script>
<script>
    

    function presenteAjax(idAlumno, idProfesor) {
        fetch('Alumno/insertarAsistenciaAjax.php', {
            method: "POST",
            body: JSON.stringify({ 
                idAlumno: idAlumno,
                idProfesor: idProfesor
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        });
    }
</script>

</script>
<script>
    document.getElementById("btnAbrirAjustes").addEventListener("click", function() {
        var modal = new bootstrap.Modal(document.getElementById("modalAjustes"));
        modal.show();
    });


    function parametrosAjax() {
    var campo1 = document.getElementById("campo1").value;
    var campo2 = document.getElementById("campo2").value;
    var campo3 = document.getElementById("campo3").value;

    fetch('Parametros.php', {
        method: "POST",
        body: JSON.stringify({
            clasesTotales: campo1,
            porcentajePromocion: campo2,
            porcentajeRegular: campo3
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            
        
        })
        location.reload();
    }

</script>
</body>
</html>