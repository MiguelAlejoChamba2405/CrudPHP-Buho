<?php
// controller/registro_personal.php
include '../model/conx.php';

if (isset($_POST['btnModificar'])) {
    // Capturar los valores del formulario
    $id_usuario = $_POST['id_usuario'];  
    $nombre = $_POST['m_usuario_nombre'];
    $numero = $_POST['m_usuario_numero'];
    $etiqueta = $_POST['m_etiqueta_id'];

    // Verifica que las variables no estén vacías antes de proceder
    if (!empty($nombre) && !empty($numero) && !empty($etiqueta)) {
        // Preparar la consulta para actualizar el usuario
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, numero = ?, etiqueta_id = ? WHERE id = ?");
        $stmt->bind_param('sssi', $nombre, $numero, $etiqueta, $id_usuario);

        // Ejecuta la consulta y verifica si se realizó correctamente
        if ($stmt->execute()) {
            echo 'Usuario modificado correctamente.';
        } else {
            echo 'Error al modificar el usuario: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        echo 'Todos los campos son obligatorios.';
    }

    // Cierra la conexión
    $conexion->close();
}
?>
