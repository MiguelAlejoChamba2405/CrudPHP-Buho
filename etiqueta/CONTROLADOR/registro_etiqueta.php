<?php
//registro de etiqueta
//verifica si el boton se presiono
if (!empty($_POST["btn_submit"])) {
    //verifica si el campo de texto no esta vacio
    if (!empty($_POST["etiqueta_nombre"]) and !empty($_POST["etiqueta_color"])) {
        //Crear variables de almacenamiento de datos
        $nombre=$_POST["etiqueta_nombre"];
        $color=$_POST["etiqueta_color"];

        $sql=$conn->query("insert into etiquetas(nombre,color) values('$nombre','$color')");
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