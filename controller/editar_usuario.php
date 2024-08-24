<?php
// controller/registro_personal.php
include '../model/conx.php';

if (isset($_POST['btnModificar'])) {
    $id_persona = $_POST['id_persona'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $fecha = $_POST['fecha'];
    $correo = $_POST['correo'];

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
