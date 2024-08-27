<?php
include '../model/conx.php';

if (isset($_POST['id'])) {
    // Obtén el ID del usuario a eliminar
    $id = intval($_POST['id']);

    // Prepara la consulta para eliminar el usuario
    $stmt = $conexion->prepare("DELETE FROM etiquetas WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        header('Location: ../etiquetas.php?eliminado=1');
    } else {
        header('Location: ../etiquetas.php?error=1');
    }

    // Cierra la declaración y la conexión
    $stmt->close();
} else {
    header('Location: ../etiquetas.php?error=1');
}

$conexion->close();
?>
