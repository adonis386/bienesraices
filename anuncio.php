<?php 
include 'includes/templates/header.php'; 

require 'includes/config/database.php';
$db = conectarDB();

// Validar el ID
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /');
}

// Obtener los datos de la propiedad
$query = "SELECT * FROM propiedades WHERE id = {$id}";
$resultado = mysqli_query($db, $query);

if ($resultado->num_rows === 0) {
    header('Location: /');
}

$propiedad = mysqli_fetch_assoc($resultado);

?>

<main class="contenedor seccion contenido-centrado">
    <h1><?php echo htmlspecialchars($propiedad['titulo']); ?></h1>

    <img loading="lazy" src="imagenes/<?php echo $propiedad['imagen']; ?>" alt="imagen propiedad">

    <div class="contenido-propiedad">
        <p><?php echo htmlspecialchars($propiedad['descripcion']); ?></p>
    </div>
</main>
    
    <?php 
    // Cerrar la conexión
    mysqli_close($db);
    include 'includes/templates/footer.php'; ?>

    <script src="build/js/bundle.min.js"></script>
</body>
</php>