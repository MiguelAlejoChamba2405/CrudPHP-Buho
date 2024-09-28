<?php
include '../model/conx.php';

if (isset($_POST['id_contacto'])) {
    // Obtén el ID del contacto a eliminar
    $id_contacto = intval($_POST['id_contacto']);  

    // Iniciar transacción para asegurar que ambas operaciones se realicen correctamente
    $conexion->begin_transaction();

    try {
        // Eliminar las relaciones de etiquetas del contacto
        $stmt = $conexion->prepare("DELETE FROM contacto_etiquetas WHERE contacto_id = ?");
        $stmt->bind_param("i", $id_contacto);
        $stmt->execute();
        $stmt->close();

        // Eliminar el contacto
        $stmt = $conexion->prepare("DELETE FROM contactos WHERE id = ?");
        $stmt->bind_param("i", $id_contacto);
        $stmt->execute();
        $stmt->close();

        // Confirmar la transacción
        $conexion->commit();
        header('Location: ../contactos.php?eliminado=1');
    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        $conexion->rollback();
        header('Location: ../contactos.php?error=1');
    }
} else {
    header('Location: ../contactos.php?error=1');
}

$conexion->close();
?>