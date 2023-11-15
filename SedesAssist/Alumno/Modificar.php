<?php 
require_once('BD/conexion.php');
class Modificar {
    private $conn;

    function __construct($conn){
        $this->conn = $conn;
    }

    public function modificar($alumno) {

        $idAlumno = $alumno['idAlumno'];
        $nombre = $alumno['nombre'];
        $apellido = $alumno['apellido'];
        $fechaNacimiento = $alumno['fechaNacimiento'];
        $dni = $alumno['dni'];

        if ($nombre && $apellido && $fechaNacimiento <> null) {
            $query = "UPDATE alumnos SET nombre=:nombre, apellido=:apellido, fechaNacimiento=:fechaNacimiento, dni=:dni
                      WHERE idAlumno = $idAlumno";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
            $stmt->bindParam(':dni',$dni);
            $stmt->execute();
            $boolean = true;
            return $boolean;

        }else{
            $boolean = false;
            return $boolean;
        }
    }
}
?>