<?php
// filepath: /c:/Users/Usuario/Desktop/bienesRaicesPHP_inicio/includes/templates/anuncios.php
require 'includes/config/database.php';
$db = conectarDB();

// Consultar la base de datos
$query = "SELECT * FROM propiedades";
$resultado = mysqli_query($db, $query);
?>

<div class="contenedor-anuncios">
    <?php while ($propiedad = mysqli_fetch_assoc($resultado)): ?>
        <div class="anuncio">
            <picture>
                <source srcset="imagenes/<?php echo $propiedad['imagen']; ?>" type="image/webp">
                <source srcset="imagenes/<?php echo $propiedad['imagen']; ?>" type="image/jpeg">
                <img loading="lazy" src="imagenes/<?php echo $propiedad['imagen']; ?>" alt="anuncio">
            </picture>

            <div class="contenido-anuncio">
                <h3><?php echo htmlspecialchars($propiedad['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($propiedad['descripcion']); ?></p>
                <p class="precio">$<?php echo number_format($propiedad['precio'], 2); ?></p>

                <ul class="iconos-caracteristicas">
                    <li>
                        <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                        <p><?php echo htmlspecialchars($propiedad['wc']); ?></p>
                    </li>
                    <li>
                        <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                        <p><?php echo htmlspecialchars($propiedad['estacionamiento']); ?></p>
                    </li>
                    <li>
                        <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                        <p><?php echo htmlspecialchars($propiedad['habitaciones']); ?></p>
                    </li>
                </ul>

                <a href="anuncio.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">
                    Ver Propiedad
                </a>
            </div><!--.contenido-anuncio-->
        </div><!--.anuncio-->
    <?php endwhile; ?>
</div><!--.anuncios-->

<?php
// Cerrar la conexión
mysqli_close($db);
?>