<?php
include "../model/conx.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $numero = $_POST['numero'];
    $etiquetas = $_POST['etiqueta_id']; // Este es un array con los IDs de las etiquetas seleccionadas

    // Iniciar transacción para asegurar consistencia
    $conexion->begin_transaction();

    try {
        // Actualizar información del usuario
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, numero=? WHERE id=?");
        $stmt->bind_param('ssi', $nombre, $numero, $id);
        $stmt->execute();
        $stmt->close();

        // Eliminar etiquetas actuales del usuario
        $stmt = $conexion->prepare("DELETE FROM usuario_etiquetas WHERE usuario_id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        // Insertar nuevas etiquetas
        $stmt = $conexion->prepare("INSERT INTO usuario_etiquetas (usuario_id, etiqueta_id) VALUES (?, ?)");
        foreach ($etiquetas as $etiqueta_id) {
            $stmt->bind_param('ii', $id, $etiqueta_id);
            $stmt->execute();
        }
        $stmt->close();

        // Confirmar transacción
        $conexion->commit();

        // Redirigir con mensaje de éxito
        header("Location: ../usuarios.php?message=Usuario actualizado correctamente.");
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        // Redirigir con mensaje de error
        header("Location: ../usuarios.php?message=Error al actualizar el usuario: " . $e->getMessage());
    }

    $conexion->close();
    exit;
}
?>