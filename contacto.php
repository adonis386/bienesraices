<?php 

require 'vendor/autoload.php';

include 'includes/templates/header.php'; ?>

<?php
$errores = [];
$mensajeEnviado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $mensaje = htmlspecialchars($_POST['mensaje']);
    $tipo = htmlspecialchars($_POST['tipo']);
    $precio = filter_var($_POST['precio'], FILTER_SANITIZE_NUMBER_INT);
    $contacto = htmlspecialchars($_POST['contacto']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $telefono = htmlspecialchars($_POST['telefono']);

    // Validar
    if (!$nombre) {
        $errores[] = "El nombre es obligatorio";
    }
    if (!$mensaje) {
        $errores[] = "El mensaje es obligatorio";
    }
    if (!$tipo) {
        $errores[] = "El tipo de operación es obligatorio";
    }
    if (!$precio) {
        $errores[] = "El precio o presupuesto es obligatorio";
    }
    if (!$contacto) {
        $errores[] = "El método de contacto es obligatorio";
    }
    if ($contacto === 'email' && !$email) {
        $errores[] = "El correo electrónico es obligatorio y debe ser válido";
    }
    if ($contacto === 'telefono' && !$telefono) {
        $errores[] = "El teléfono es obligatorio";
    }

    // Si no hay errores, enviar el correo
    if (empty($errores)) {
        $destinatario = "tu_correo@example.com";
        $asunto = "Nuevo mensaje de contacto";
        $contenido = "Nombre: $nombre\n";
        $contenido .= "Mensaje: $mensaje\n";
        $contenido .= "Tipo: $tipo\n";
        $contenido .= "Precio: $precio\n";
        $contenido .= "Contacto: $contacto\n";
        if ($contacto === 'email') {
            $contenido .= "Email: $email\n";
        } else {
            $contenido .= "Teléfono: $telefono\n";
        }

        // Enviar el correo
        $enviado = mail($destinatario, $asunto, $contenido);

        if ($enviado) {
            $mensajeEnviado = true;
        } else {
            $errores[] = "Hubo un error al enviar el mensaje";
        }
    }
}
?>

<main class="contenedor seccion">
    <h1>Contacto</h1>

    <?php if ($mensajeEnviado): ?>
        <p class="alerta exito">Mensaje enviado correctamente</p>
    <?php endif; ?>

    <?php foreach ($errores as $error): ?>
        <p class="alerta error"><?php echo $error; ?></p>
    <?php endforeach; ?>

    <picture>
        <source srcset="build/img/destacada3.webp" type="image/webp">
        <source srcset="build/img/destacada3.jpg" type="image/jpeg">
        <img loading="lazy" src="build/img/destacada3.jpg" alt="Imagen Contacto">
    </picture>

    <h2>Llene el formulario de Contacto</h2>

    <form class="formulario" method="POST" action="contacto.php">
        <fieldset>
            <legend>Información Personal</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Tu Nombre">

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje"></textarea>
        </fieldset>

        <fieldset>
            <legend>Información sobre la propiedad</legend>

            <label for="tipo">Vende o Compra:</label>
            <select id="tipo" name="tipo">
                <option value="" disabled selected>-- Seleccione --</option>
                <option value="Compra">Compra</option>
                <option value="Vende">Vende</option>
            </select>

            <label for="precio">Precio o Presupuesto</label>
            <input type="number" placeholder="Tu Precio o Presupuesto" id="precio" name="precio">
        </fieldset>

        <fieldset>
            <legend>Información sobre la propiedad</legend>

            <p>Como desea ser contactado</p>

            <div class="forma-contacto">
                <label for="contacto-telefono">Teléfono</label>
                <input type="radio" id="contacto-telefono" name="contacto" value="telefono">

                <label for="contacto-email">E-mail</label>
                <input type="radio" id="contacto-email" name="contacto" value="email">
            </div>

            <div id="contacto"></div>
        </fieldset>

        <fieldset>
            <legend>Información de contacto</legend>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Tu E-mail">

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" placeholder="Tu Teléfono">
        </fieldset>

        <input type="submit" value="Enviar" class="boton boton-verde">
    </form>
</main>

<?php include 'includes/templates/footer.php'; ?>

<script src="build/js/bundle.min.js"></script>
</body>
</html>