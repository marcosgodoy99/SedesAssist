<?php 

require_once ('BD/conexion.php');

class Baja{ 
    private $conn;
    private $dni;
    private $boolean = 0;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function delete($idProfesor) {
        $query = "DELETE profesores, asistencias FROM profesores
        LEFT JOIN asistencias ON profesores.idProfesor = asistencias.idProfesor
        WHERE profesores.idProfesor = :idProfesor";
        $STMT = $this->conn->prepare($query);
        $STMT->bindParam(':idProfesor', $idProfesor);
        $STMT->execute();
        $this->boolean = 1;

    }
}




?>