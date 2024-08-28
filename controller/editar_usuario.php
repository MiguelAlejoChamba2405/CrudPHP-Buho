<?php
include "../model/conx.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $numero = $_POST['numero'];
    $etiqueta = $_POST['etiqueta_id']; // Asegúrate de que este campo sea 'etiqueta_id'

    // Corrección de la consulta SQL (campo `etiquetas` debe ser `etiqueta_id`)
    $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, numero=?, etiquetas=? WHERE id=?");
    $stmt->bind_param('sssi', $nombre, $numero, $etiqueta, $id);

    if ($stmt->execute()) {
        // Redirigir con mensaje de éxito
        header("Location: ../usuarios.php?message=Usuario actualizado correctamente.");
    } else {
        // Redirigir con mensaje de error
        header("Location: ../usuarios.php?message=Error al actualizar el usuario: " . $stmt->error);
    }

    $stmt->close();
    $conexion->close();
    exit;
}
?>