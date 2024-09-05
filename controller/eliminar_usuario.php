<?php
include '../model/conx.php';

if (isset($_POST['id_usuario'])) {
    // Obtén el ID del usuario a eliminar
    $id_usuario = intval($_POST['id_usuario']);  

    // Iniciar transacción para asegurar que ambas operaciones se realicen correctamente
    $conexion->begin_transaction();

    try {
        // Eliminar las relaciones de etiquetas del usuario
        $stmt = $conexion->prepare("DELETE FROM usuario_etiquetas WHERE usuario_id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();

        // Eliminar el usuario
        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();

        // Confirmar la transacción
        $conexion->commit();
        header('Location: ../usuarios.php?eliminado=1');
    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        $conexion->rollback();
        header('Location: ../usuarios.php?error=1');
    }
} else {
    header('Location: ../usuarios.php?error=1');
}

$conexion->close();
?>