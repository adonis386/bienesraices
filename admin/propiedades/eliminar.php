<?php
// filepath: /c:/Users/Usuario/Desktop/bienesRaicesPHP_inicio/admin/propiedades/eliminar.php
require '../../includes/config/database.php';
$db = conectarDB();

// Validar la URL por ID válido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
}

// Obtener la propiedad
$query = "SELECT imagen FROM propiedades WHERE id = ${id}";
$resultado = mysqli_query($db, $query);
$propiedad = mysqli_fetch_assoc($resultado);

// Eliminar la imagen
$carpetaImagenes = '../../imagenes/';
if (file_exists($carpetaImagenes . $propiedad['imagen'])) {
    unlink($carpetaImagenes . $propiedad['imagen']);
}

// Eliminar la propiedad
$query = "DELETE FROM propiedades WHERE id = ${id}";
$resultado = mysqli_query($db, $query);

if ($resultado) {
    header('Location: /admin?resultado=3');
} else {
    echo "Error: " . mysqli_error($db);
}
?>