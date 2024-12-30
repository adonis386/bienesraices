<?php 
    $resultado = $_GET['resultado'] ?? null;
    include '../includes/templates/header.php'; 

    require '../includes/config/database.php';
    $db = conectarDB();

    // Consultar la base de datos
    $query = "SELECT * FROM propiedades";
    $resultadoConsulta = mysqli_query($db, $query);
?>
    
<main class="contenedor seccion">
    <h1>Administrador de Bienes Raices</h1>
    <?php if (intval($resultado) === 1): ?>
        <p class="alerta exito">Propiedad creada correctamente</p>
    <?php elseif (intval($resultado) === 2): ?>
        <p class="alerta exito">Propiedad actualizada correctamente</p>
    <?php elseif (intval($resultado) === 3): ?>
        <p class="alerta error">Propiedad eliminada correctamente</p>
    <?php endif; ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
            <tr>
                <td class="id"><?php echo $propiedad['id']; ?></td>
                <td class="titulo"><?php echo $propiedad['titulo']; ?></td>
                <td><img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"></td>
                <td>$<?php echo $propiedad['precio']; ?></td>
                <td>
                    <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    <a href="/admin/propiedades/eliminar.php?id=<?php echo $propiedad['id']; ?>" class="boton-rojo-block">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php 
    // Cerrar la conexión
    mysqli_close($db);

    include '../includes/templates/footer.php'; 
?>

<script src="/build/js/bundle.min.js"></script>
</body>
</html>