<?php
//registro de etiqueta
?>
<?php
if (isset($_POST['btn_submit'])) {
    $nombre = $_POST['etiqueta_nombre'];
    $color = $_POST['etiqueta_color'];

    // Insertar el usuario
    $conexion->query("INSERT INTO etiquetas(nombre,color) values('$nombre','$color')");
}
?>
