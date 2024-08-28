<?php
include "../model/conx.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $color = $_POST['color'];

    $stmt = $conexion->prepare("UPDATE etiquetas SET nombre=?, color=? WHERE id=?");
    $stmt->bind_param('ssi', $nombre, $color, $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Etiqueta actualizada correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error al actualizar la etiqueta: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conexion->close();

    header("Location: ../etiquetas.php");
    exit;
}
?>