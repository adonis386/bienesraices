<?php

require '../../includes/config/database.php';
$db = conectarDB();

$errores = [];

// Obtener vendedores
$query = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $query);
$vendedores = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $vendedores[] = $row;
}

$vendedorId = ''; // Definir la variable $vendedorId

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Asignar los valores a las variables de imagenes
    $imagen = $_FILES['imagen'] ?? null;

    $titulo = mysqli_real_escape_string($db, $_POST['titulo'] ?? '');
    $precio = mysqli_real_escape_string($db, $_POST['precio'] ?? '');
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion'] ?? '');
    $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones'] ?? '');
    $wc = mysqli_real_escape_string($db, $_POST['wc'] ?? '');
    $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento'] ?? '');
    $vendedorId = mysqli_real_escape_string($db, $_POST['vendedorId'] ?? '');

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

    if ($imagen && $imagen['size'] > 1 * 1024 * 1024) { // 100KB
        $errores[] = "La imagen es muy pesada. Debe ser menor a 1MB.";
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($errores)) {

        //crear carpeta para subir imagenes
        $carpetaImagenes = '../../imagenes/';
        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }
        // Subir la imagen
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);

        $query = "INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, vendedorId, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'sdsiiiss', $titulo, $precio, $descripcion, $habitaciones, $wc, $estacionamiento, $vendedorId, $nombreImagen);

        $resultado = mysqli_stmt_execute($stmt);

        if ($resultado) {
            //redireccionar al usuario;
            header('Location: /admin?resultado=1');
        }

        mysqli_stmt_close($stmt);
    }
}

include '../../includes/templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Crear</h1>
    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form style="margin-top: 2rem;" action="/admin/propiedades/crear.php" class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Informacion General de Nuestra Propiedad</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo htmlspecialchars($titulo ?? ''); ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo htmlspecialchars($precio ?? ''); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($descripcion ?? ''); ?></textarea>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
        </fieldset>

        <fieldset>
            <legend>Informacion Propiedad</legend>
            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($habitaciones ?? ''); ?>">

            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($wc ?? ''); ?>">

            <label for="estacionamiento">Estacionamiento</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($estacionamiento ?? ''); ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>
            <label for="vendedorId">Vendedor:</label>
            <select id="vendedorId" name="vendedorId">
                <option value="">-- Seleccione --</option>
                <?php foreach ($vendedores as $vendedor) : ?>
                    <option value="<?php echo $vendedor['id']; ?>" <?php echo $vendedorId == $vendedor['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vendedor['nombre'] . " " . $vendedor['apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php include '../../includes/templates/footer.php'; ?>

<script src="/build/js/bundle.min.js"></script>
</body>
</php>