<?php 

require_once('BD/conexion.php');

class Baja { 
    private $conn;
    private $dni;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function eliminar($idAlumno) {
        
        $query = "DELETE FROM alumnos WHERE idAlumno = $idAlumno";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $booelan = 1;
        return $booelan;
        
    }
}
?>