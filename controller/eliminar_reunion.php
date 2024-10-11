<?php
include '../model/conx.php';

if (isset($_POST['id'])) {
    // Obtén el ID de la reunión a eliminar
    $id = intval($_POST['id']);  

    // Iniciar transacción para asegurar que todas las operaciones se realicen correctamente
    $conexion->begin_transaction();

    try {
        // Eliminar las relaciones de etiquetas de la reunión
        $stmt = $conexion->prepare("DELETE FROM reunion_etiquetas WHERE reunion_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Eliminar los contactos asociados a la reunión
        $stmt = $conexion->prepare("DELETE FROM reunion_usuarios WHERE reunion_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Eliminar la reunión
        $stmt = $conexion->prepare("DELETE FROM reuniones WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Confirmar la transacción
        $conexion->commit();
        header('Location: ../reuniones.php?eliminado=1');
    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        $conexion->rollback();
        header('Location: ../reuniones.php?error=1');
    }
} else {
    header('Location: ../reuniones.php?error=1');
}

$conexion->close();
?>