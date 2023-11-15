<?php 

require_once('../BD/conexion.php');

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (isset($data['idAlumno']) && isset($data['idProfesor'])) {

        $idAlumno = $data['idAlumno'];
        $idProfesor = $data['idProfesor'];
    
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date('Y-m-d.H:i:s');
        
        $query = "INSERT INTO asistencias(idAlumno,fechaAsistencia,idProfesor) VALUES (:idAlumno,:fechaAsistencia,:idProfesor)";
        $STMT = $conn->prepare($query);
        $STMT->bindParam(":idAlumno", $idAlumno);
        $STMT->bindParam(":fechaAsistencia", $fecha);
        $STMT->bindParam(":idProfesor", $idProfesor);
        $STMT->execute();
    }
?>