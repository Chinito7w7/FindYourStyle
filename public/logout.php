<?php
session_start();

// Eliminar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir a la página de inicio o login
header("Location: login.php");
exit();
?>