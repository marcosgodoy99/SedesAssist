<?php 

require_once('BD/conexion.php');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$clasesTotales = $data['clasesTotales'];
$porcentajePromocion = $data['porcentajePromocion'];
$porcentajeRegular = $data['porcentajeRegular'];
$IDP = 1;

$query = "UPDATE parametros SET clasesTotales=:clasesTotales, porcentajeRegular=:porcentajeRegular, porcentajePromocion=:porcentajePromocion 
           WHERE IDP = $IDP";
$STMT = $conn->prepare($query);
$STMT->bindParam(":clasesTotales", $clasesTotales);
$STMT->bindParam(":porcentajePromocion", $porcentajePromocion);
$STMT->bindParam(":porcentajeRegular", $porcentajeRegular);
$STMT->execute();

   
 ?> 
   