<?php
include "../model/conx.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $etiquetas = isset($_POST['etiquetas']) ? $_POST['etiquetas'] : []; // Asegúrate de que `etiquetas` siempre sea un array

    // Iniciar transacción para asegurar consistencia
    $conexion->begin_transaction();

    try {
        // Actualizar información del usuario
        $stmt = $conexion->prepare("UPDATE reuniones SET nombre=?, fecha_inicio=?, fecha_fin=? WHERE id=?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de actualización: " . $conexion->error);
        }
        $stmt->bind_param('sssi', $nombre, $fecha_inicio, $fecha_fin, $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta de actualización: " . $stmt->error);
        }
        $stmt->close();

        // Eliminar etiquetas actuales del usuario
        $stmt = $conexion->prepare("DELETE FROM reunion_etiquetas WHERE reunion_id=?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de eliminación de etiquetas: " . $conexion->error);
        }
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta de eliminación de etiquetas: " . $stmt->error);
        }
        $stmt->close();

        // Insertar nuevas etiquetas
        $stmt = $conexion->prepare("INSERT INTO reunion_etiquetas (reunion_id, etiqueta_id) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de inserción de etiquetas: " . $conexion->error);
        }
        foreach ($etiquetas as $etiqueta_id) {
            $stmt->bind_param('ii', $id, $etiqueta_id);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta de inserción de etiquetas: " . $stmt->error);
            }
        }
        $stmt->close();

        // Confirmar transacción
        $conexion->commit();

        // Redirigir con mensaje de éxito
        header("Location: ../reuniones.php?message=" . urlencode("Reunión actualizada correctamente."));
        exit;
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();

        // Redirigir con mensaje de error
        header("Location: ../reuniones.php?message=" . urlencode("Error al actualizar la reunión: " . $e->getMessage()));
        exit;
    }

    $conexion->close();
}
?>
