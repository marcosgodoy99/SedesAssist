<?php   

require_once('BD/conexion.php');

class Promediar
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function calcular()
    {
            $query = "SELECT * FROM parametros";
            $STMT = $this->conn->prepare($query);
            $STMT->execute();
            $parametros = $STMT->fetchAll();

            $clasesTotales = $parametros[0]['clasesTotales'];
            $porcentajePromocion = $parametros[0]['porcentajePromocion'];
            $porcentajeRegular = $parametros[0]['porcentajeRegular'];

            $orden = "apellido";
            $direccion = "ASC";

            $query = "SELECT * FROM alumnos
                      ORDER BY $orden $direccion";
            $STMT = $this->conn->prepare($query);
            $STMT->execute();
            $alumnos = $STMT->fetchAll();

            $resultados = array();

            foreach ($alumnos as $alumno) {

                $idAlumno = $alumno['idAlumno'];
                $query = "SELECT COUNT(fechaAsistencia) FROM asistencias
                          WHERE idAlumno =  $idAlumno";
                $STMT = $this->conn->prepare($query);
                $STMT->execute();
                $asistencia = $STMT->fetch();
     

            if ($clasesTotales > 0 && $clasesTotales <= 190) {
            $valor = ($asistencia[0] / $clasesTotales * 100);
            $porcentaje = round($valor, 2);

            if ($porcentaje >= $porcentajePromocion) {
                $resultados[]= '<div style="color:green">' . $porcentaje . ' % </div>';
            } elseif ($porcentaje >= $porcentajeRegular) {
                $resultados[]='<div style="color:yellow">' . $porcentaje . ' % </div>';
            } else {
                $resultados[]='<div style="color:red">' . $porcentaje . ' % </div>';
            }
        } else {
            
            $resultados[]='<div style="color:gray">Clases totales fuera de rango</div>';
        }
    }
    return $resultados;
   }
}

?>