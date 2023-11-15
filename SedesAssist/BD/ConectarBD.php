<?php 

function conectarBaseDeDatos() {
    $host = "localhost"; // Cambia esto al nombre de tu host de la base de datos
    $usuario = "root"; // Cambia esto al nombre de usuario de la base de datos
    $contrasena = ""; // Cambia esto a la contraseña de la base de datos
    $nombreBaseDeDatos = "sistema_toma_asistencias"; // Cambia esto al nombre de la base de datos

    try {
        $dsn = "mysql:host=$host;dbname=$nombreBaseDeDatos";
        $conexion = new PDO($dsn, $usuario, $contrasena);
        return $conexion;
    } catch (PDOException $e) {
        // Manejo de errores (puedes personalizarlo según tus necesidades)
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
}

?>