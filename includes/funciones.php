<?php
require 'includes/app.php';
function incluirTemplate($nombre, bool $inicio = false) {
    include TEMPLATES_URL . "/{$nombre}.php";
}