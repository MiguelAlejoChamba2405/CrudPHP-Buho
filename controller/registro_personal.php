<?php
if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["usuario_nombre"]) and !empty($_POST["usuario_numero"]) and !empty($_POST["etiqueta_id"])) {
        $nombre=$_POST["usuario_nombre"];
        $numero=$_POST["usuario_numero"];
        $etiquetas=$_POST["etiqueta_id"];

        $sql=$conexion->query("insert into usuarios(nombre,numero,etiquetas) values('$nombre','$numero','$etiquetas')");
        if ($sql==1) {
            echo '<div class="alert alert-success">Persona registrada correctamente</div>';
        } else {
            echo '<div class="alert alert-danger">Error al registrar persona</div>';
        }
    } else {
        echo '<div class="alert alert-warning">Alguno de los campos está vacío</div>';
    }
}
?>
