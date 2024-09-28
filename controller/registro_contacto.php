<?php
if (isset($_POST['btn_submit'])) {
    $nombre = $_POST['contacto_nombre'];
    $numero = $_POST['contacto_numero'];
    $etiquetas = $_POST['etiqueta_id']; 

    // Insertar el contacto
    $conexion->query("INSERT INTO contactos (nombre, numero) VALUES ('$nombre', '$numero')");
    $contacto_id = $conexion->insert_id;

    // Insertar las etiquetas del contacto
    foreach ($etiquetas as $etiqueta_id) {
        $conexion->query("INSERT INTO contacto_etiquetas (contacto_id, etiqueta_id) VALUES ('$contacto_id', '$etiqueta_id')");
    }
}
?>
