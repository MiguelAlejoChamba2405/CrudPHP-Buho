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
        // Actualizar información del contacto
        $stmt = $conexion->prepare("UPDATE contactos SET nombre=?, numero=? WHERE id=?");
        $stmt->bind_param('ssi', $nombre, $numero, $id);
        $stmt->execute();
        $stmt->close();

        // Eliminar etiquetas actuales del contacto
        $stmt = $conexion->prepare("DELETE FROM contacto_etiquetas WHERE contacto_id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        // Insertar nuevas etiquetas
        $stmt = $conexion->prepare("INSERT INTO contacto_etiquetas (contacto_id, etiqueta_id) VALUES (?, ?)");
        foreach ($etiquetas as $etiqueta_id) {
            $stmt->bind_param('ii', $id, $etiqueta_id);
            $stmt->execute();
        }
        $stmt->close();

        // Confirmar transacción
        $conexion->commit();

        // Redirigir con mensaje de éxito
        header("Location: ../contactos.php?message=contacto actualizado correctamente.");
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        // Redirigir con mensaje de error
        header("Location: ../contactos.php?message=Error al actualizar el contacto: " . $e->getMessage());
    }

    $conexion->close();
    exit;
}
?>