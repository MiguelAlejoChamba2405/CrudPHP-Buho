<?php
include '../model/conx.php';

if (isset($_POST['btn_submit'])) {
    $id_persona = $_POST['id_persona'];
    $nombre = $_POST['etiqueta_nombre'];
    $color = $_POST['etiqueta_color'];

    // Preparar la consulta para actualizar el usuario
    $stmt = $conexion->prepare("UPDATE usuario SET nombre = ?, apellido = ?, dni = ?, fecha_nac = ?, correo = ? WHERE id_persona = ?");
    $stmt->bind_param('sssssi', $nombre, $apellido, $dni, $fecha, $correo, $id_persona);

    if ($stmt->execute()) {
        echo 'Usuario modificado correctamente.';
    } else {
        echo 'Error al modificar el usuario: ' . $stmt->error;
    }
    $stmt->close();
    $conexion->close();
}
?>