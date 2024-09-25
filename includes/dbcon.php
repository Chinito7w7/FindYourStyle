<?php
$host = 'localhost';  // Servidor de la base de datos
$dbname = 'findyourstyle';  // Nombre de la base de datos
$username = 'root';  // Usuario de la base de datos
$password = '';  // Contraseña de la base de datos

try {
    // Crear una conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar el error en caso de fallo en la conexión
    echo "Error de conexión: " . $e->getMessage();
}
?>
