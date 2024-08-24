<?php
include '../model/conx.php';

if (isset($_POST['id_persona'])) {
    // Obtén el ID del usuario a eliminar
    $id_persona = intval($_POST['id_persona']);

    // Prepara la consulta para eliminar el usuario
    $stmt = $conexion->prepare("DELETE FROM usuario WHERE id_persona = ?");
    $stmt->bind_param("i", $id_persona);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        header('Location: ../index.php?eliminado=1');
    } else {
        header('Location: ../index.php?error=1');
    }

    // Cierra la declaración y la conexión
    $stmt->close();
} else {
    header('Location: ../index.php?error=1');
}

$conexion->close();
?>
