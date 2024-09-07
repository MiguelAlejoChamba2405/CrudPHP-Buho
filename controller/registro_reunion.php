<?php
include "model/conx.php";

if (isset($_POST['btn_submit'])) {
    // Obtén los valores del formulario
    $nombre = $_POST['nombre'];
    $etiquetas = $_POST['etiquetas']; // Array de etiquetas seleccionadas
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Inserta los datos en la tabla 'reuniones'
    $stmt = $conexion->prepare("INSERT INTO reuniones (nombre, fecha_inicio, fecha_fin) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $nombre, $fecha_inicio, $fecha_fin);
    if ($stmt->execute()) {
        $reunion_id = $stmt->insert_id; // Obtener el ID de la reunión recién insertada
        $stmt->close();

        // Inserta las etiquetas asociadas en la tabla intermedia 'reunion_etiquetas'
        $stmt_etiquetas = $conexion->prepare("INSERT INTO reunion_etiquetas (reunion_id, etiqueta_id) VALUES (?, ?)");
        foreach ($etiquetas as $etiqueta_id) {
            $stmt_etiquetas->bind_param('ii', $reunion_id, $etiqueta_id);
            $stmt_etiquetas->execute();
        }
        $stmt_etiquetas->close();

    } 
}
?>