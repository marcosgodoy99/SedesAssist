<?php 
require_once ('BD/conexion.php');

class Alta{ 
    private $conn;
    private $boolean = 0;
    

    function __construct($alumno,$conn) {
        $nombre = $alumno['nombre'];
        $apellido = $alumno['apellido'];
        $fechaNacimiento = $alumno['fechaNacimiento'];
        $dni = $alumno['dni'];
        $this->conn=$conn;

        if ($nombre && $apellido && $fechaNacimiento && $dni <> null) {

            $query = "INSERT INTO alumnos (nombre, apellido, fechaNacimiento, dni) VALUES (:nombre, :apellido, :fechaNacimiento, :dni)";

            $STMT = $conn->prepare($query);
            $STMT->bindParam(":nombre", $nombre);
            $STMT->bindParam(":apellido", $apellido);
            $STMT->bindParam(":fechaNacimiento", $fechaNacimiento);
            $STMT->bindParam(":dni", $dni);
            $STMT->execute();
            $this->boolean+=1;
        }    
    }

    public function mostrarMensaje() {
            if ($this->boolean == 1) {
                return true; 
            } else {
                return false; 
            }
        }
};


?>