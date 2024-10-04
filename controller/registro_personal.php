<?php
if (isset($_POST['btn_submit'])) {
    $nombre = $_POST['usuario_nombre'];
    $numero = $_POST['usuario_numero'];
    $etiquetas = $_POST['etiqueta_id']; 

    // Insertar el usuario
    $conexion->query("INSERT INTO usuarios (nombre, numero) VALUES ('$nombre', '$numero')");
    $usuario_id = $conexion->insert_id;

    // Insertar las etiquetas del usuario
    foreach ($etiquetas as $etiqueta_id) {
        $conexion->query("INSERT INTO usuario_etiquetas (usuario_id, etiqueta_id) VALUES ('$usuario_id', '$etiqueta_id')");
    }
}
?>
