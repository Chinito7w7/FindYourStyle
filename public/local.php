<?php
session_start();
require_once '../includes/dbcon.php'; // Conexión a la base de datos

// Verificar si se ha proporcionado el ID del local en la URL
if (isset($_GET['id'])) {
    $local_id = $_GET['id'];

    // Obtener la información del local
    $query = "SELECT * FROM locals WHERE id = :local_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':local_id', $local_id);
    $stmt->execute();
    $local = $stmt->fetch();

    // Verificar si el local fue encontrado
    if (!$local) {
        echo "Local no encontrado.";
        exit();
    }

    // Obtener los servicios del local
    $query_services = "SELECT * FROM services WHERE local_id = :local_id";
    $stmt_services = $conn->prepare($query_services);
    $stmt_services->bindParam(':local_id', $local_id);
    $stmt_services->execute();
    $services = $stmt_services->fetchAll();

    // Obtener las reseñas del local
    $query_reviews = "SELECT reviews.*, users.name FROM reviews 
                      JOIN users ON reviews.user_id = users.id
                      WHERE reviews.local_id = :local_id";
    $stmt_reviews = $conn->prepare($query_reviews);
    $stmt_reviews->bindParam(':local_id', $local_id);
    $stmt_reviews->execute();
    $reviews = $stmt_reviews->fetchAll();

    // Verificar si el usuario es el propietario del local
    $user_id = $_SESSION['user_id'] ?? null;
    $is_owner = false;

    if ($user_id) {
        // Comprobar si el usuario es dueño del local
        $query_user = "SELECT is_local_owner FROM users WHERE id = :user_id";
        $stmt_user = $conn->prepare($query_user);
        $stmt_user->bindParam(':user_id', $user_id);
        $stmt_user->execute();
        $user = $stmt_user->fetch();

        // Verificar si el usuario es el propietario del local
        $query_owner = "SELECT owner_id FROM locals WHERE id = :local_id";
        $stmt_owner = $conn->prepare($query_owner);
        $stmt_owner->bindParam(':local_id', $local_id);
        $stmt_owner->execute();
        $local_owner = $stmt_owner->fetch();

        $is_owner = $user['is_local_owner'] && $local_owner['owner_id'] == $user_id;
    }
} else {
    echo "ID del local no especificado.";
    exit();
}

// Manejar el formulario de reseñas (si es que se envió una)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_owner) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $query_add_review = "INSERT INTO reviews (local_id, user_id, rating, comment) VALUES (:local_id, :user_id, :rating, :comment)";
    $stmt_add_review = $conn->prepare($query_add_review);
    $stmt_add_review->bindParam(':local_id', $local_id);
    $stmt_add_review->bindParam(':user_id', $user_id);
    $stmt_add_review->bindParam(':rating', $rating);
    $stmt_add_review->bindParam(':comment', $comment);
    $stmt_add_review->execute();

    // Redirigir a la página del local después de agregar la reseña
    header("Location: local.php?id=" . $local_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($local['name']); ?> - Find Your Style</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/local.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../assets/img/logo.png" alt="Logo" class="logo">
            <span class="page-name">Find Your Style</span>
        </div>

        <!-- Mostrar botón 'Personalizar Local' solo si el usuario es el dueño -->
        <?php if ($is_owner): ?>
            <a href="personalizar_local.php?id=<?php echo htmlspecialchars($local_id); ?>" class="customize-local-btn">Personalizar Local</a>
        <?php endif; ?>
        
        <a href="index.php" class="back-home-btn">Volver a la página principal</a>
    </header>

    <main>
        <div class="local-container">
            <!-- Sección de la foto y nombre del local -->
            <div class="local-photo-container">
                <?php if (isset($local['main_photo'])): ?>
                    <img src="../uploads/locals/<?php echo htmlspecialchars($local['main_photo']); ?>" alt="<?php echo htmlspecialchars($local['name'] ?? 'Local sin nombre'); ?>" class="local-photo">
                <?php else: ?>
                    <p>Este local no tiene foto principal.</p>
                <?php endif; ?>

                <?php if (isset($local['name'])): ?>
                    <h1 class="local-name"><?php echo htmlspecialchars($local['name']); ?></h1>
                <?php else: ?>
                    <h1 class="local-name">Nombre no disponible</h1>
                <?php endif; ?>
            </div>

            <!-- Sección de Servicios -->
            <div class="services-section">
                <h2>Servicios que ofrecemos</h2>
                <?php if (count($services) > 0): ?>
                    <ul>
                        <?php foreach ($services as $service): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($service['name']); ?></strong>: 
                                <?php echo htmlspecialchars($service['description']); ?> - 
                                Precio: $<?php echo htmlspecialchars(number_format($service['price'], 2)); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Este local aún no ha agregado servicios.</p>
                <?php endif; ?>
            </div>
             <!-- Botón de Reservar -->
            <div class="reserve-section">
                <a href="reservar_turno.php?local_id=<?php echo htmlspecialchars($local_id); ?>" class="reserve-btn">Reservar Servicio</a>
            </div>
            <!-- Sección de Reseñas -->
            <div class="reviews-section">
                <h2>Reseñas</h2>
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <p class="review-username">Usuario: <?php echo htmlspecialchars($review['name']); ?></p>
                            <p class="rating">Calificación: <?php echo htmlspecialchars($review['rating']); ?> / 5</p>
                            <p>Comentario: <?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay reseñas aún.</p>
                <?php endif; ?>

                <!-- Mostrar formulario de reseña si el usuario no es el propietario del local -->
                <?php if (!$is_owner && $user_id): ?>
                    <h3>Deja tu reseña</h3>
                    <form action="local.php?id=<?php echo htmlspecialchars($local_id); ?>" method="POST">
                        <label for="rating">Calificación:</label>
                        <input type="number" id="rating" name="rating" min="1" max="5" required>
                        <label for="comment">Comentario:</label>
                        <textarea id="comment" name="comment" rows="4" required></textarea>
                        <button type="submit">Enviar Reseña</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Find Your Style</p>
        <div class="footer-links">
            <a href="#">Términos de Servicio</a>
            <a href="#">Política de Privacidad</a>
        </div>
    </footer>
</body>
</html>
