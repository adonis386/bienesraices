<?php
// filepath: /c:/Users/Usuario/Desktop/bienesRaicesPHP_inicio/admin/propiedades/actualizar.php
include '../../includes/templates/header.php';
require '../../includes/config/database.php';
$db = conectarDB();

// Validar la URL por ID válido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
}

// Obtener los datos de la propiedad
$query = "SELECT * FROM propiedades WHERE id = {$id}";
$resultado = mysqli_query($db, $query);
$propiedad = mysqli_fetch_assoc($resultado);

// Obtener vendedores
$query = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $query);
$vendedores = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $vendedores[] = $row;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = mysqli_real_escape_string($db, $_POST['titulo'] ?? '');
    $precio = mysqli_real_escape_string($db, $_POST['precio'] ?? '');
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion'] ?? '');
    $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones'] ?? '');
    $wc = mysqli_real_escape_string($db, $_POST['wc'] ?? '');
    $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento'] ?? '');
    $vendedorId = mysqli_real_escape_string($db, $_POST['vendedorId'] ?? '');
    $imagen = $_FILES['imagen'] ?? null;

    // Validar
    if (!$titulo) {
        $errores[] = "Debes añadir un título";
    }
    if (!$precio) {
        $errores[] = "Debes añadir un precio";
    }
    if (!$descripcion) {
        $errores[] = "Debes añadir una descripción";
    }
    if (!$habitaciones) {
        $errores[] = "Debes añadir el número de habitaciones";
    }
    if (!$wc) {
        $errores[] = "Debes añadir el número de baños";
    }
    if (!$estacionamiento) {
        $errores[] = "Debes añadir el número de estacionamientos";
    }
    if (!$vendedorId) {
        $errores[] = "Debes elegir un vendedor";
    }

    // Validar imagen
    if ($imagen && $imagen['size'] > 1 * 1024 * 1024) { // 1MB
        $errores[] = "La imagen es muy pesada. Debe ser menor a 1MB.";
    }

    // Si no hay errores, actualizar en la base de datos
    if (empty($errores)) {
        // Subir la imagen
        if ($imagen['name']) {
            // Crear carpeta para subir imagenes
            $carpetaImagenes = '../../imagenes/';
            if (!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes);
            }

            // Generar un nombre único para la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);

            // Eliminar la imagen anterior
            if ($propiedad['imagen']) {
                unlink($carpetaImagenes . $propiedad['imagen']);
            }
        } else {
            $nombreImagen = $propiedad['imagen'];
        }

        // Actualizar la base de datos
        $query = "UPDATE propiedades SET titulo = ?, precio = ?, descripcion = ?, habitaciones = ?, wc = ?, estacionamiento = ?, vendedorId = ?, imagen = ? WHERE id = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'sdsiiissi', $titulo, $precio, $descripcion, $habitaciones, $wc, $estacionamiento, $vendedorId, $nombreImagen, $id);

        $resultado = mysqli_stmt_execute($stmt);

        if ($resultado) {
            header('Location: /admin?resultado=2');
        } else {
            echo "Error: " . mysqli_error($db);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>
    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form style="margin-top: 2rem;" action="/admin/propiedades/actualizar.php?id=<?php echo $id; ?>" class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Informacion General de Nuestra Propiedad</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo htmlspecialchars($propiedad['titulo']); ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo htmlspecialchars($propiedad['precio']); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($propiedad['descripcion']); ?></textarea>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
            <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-small">
        </fieldset>

        <fieldset>
            <legend>Informacion Propiedad</legend>
            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($propiedad['habitaciones']); ?>">

            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($propiedad['wc']); ?>">

            <label for="estacionamiento">Estacionamiento</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($propiedad['estacionamiento']); ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>
            <label for="vendedorId">Vendedor:</label>
            <select id="vendedorId" name="vendedorId">
                <option value="">-- Seleccione --</option>
                <?php foreach ($vendedores as $vendedor) : ?>
                    <option value="<?php echo $vendedor['id']; ?>" <?php echo $propiedad['vendedorId'] == $vendedor['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vendedor['nombre'] . " " . $vendedor['apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>

<?php include '../../includes/templates/footer.php'; ?>

<script src="/build/js/bundle.min.js"></script>
</body>
</html>