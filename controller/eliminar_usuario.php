<?php
include '../model/conx.php';

if (isset($_POST['id_usuario'])) {
    // Obtén el ID del usuario a eliminar
    $id_usuario = intval($_POST['id_usuario']);  

    // Prepara la consulta para eliminar el usuario
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");  
    $stmt->bind_param("i", $id_usuario);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        header('Location: ../usuarios.php?eliminado=1');
    } else {
        header('Location: ../usuarios.php?error=1');
    }

    // Cierra la declaración y la conexión
    $stmt->close();
} else {
    header('Location: ../usuarios.php?error=1');
}

$conexion->close();
?>
