<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    $conn = new PDO('pgsql:host=localhost;dbname=tu_base_datos', 'usuario', 'contraseña');

    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar credenciales
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Iniciar sesión
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php'); // Redirige al dashboard
    } else {
        echo "Credenciales incorrectas.";
    }
}
?>
