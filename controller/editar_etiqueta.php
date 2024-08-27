<?php
include "../model/conx.php";

// Obtener el id de la etiqueta a editar
$id = $_GET["id"];
$sql = $conexion->query("SELECT * FROM etiquetas WHERE id=$id");

// Verificar si se ha enviado el formulario
if (isset($_POST['modificar_btn_submit'])) {
    $nombre = $_POST['modificar_etiqueta_nombre'];
    $color = $_POST['modificar_etiqueta_color'];

    // Preparar y ejecutar la consulta de actualización
    $stmt = $conexion->prepare("UPDATE etiquetas SET nombre=?, color=? WHERE id=?");
    $stmt->bind_param('ssi', $nombre, $color, $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Etiqueta actualizada correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error al actualizar la etiqueta: " . $stmt->error . "</div>";
    }
    $stmt->close();
    $conexion->close();
    // Redirigir después de actualizar
    header("Location: ../etiquetas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c5ebb6dec6.js" crossorigin="anonymous"></script>
    <title>MODIFICAR ETIQUETA</title>
</head>

<body>
    <form class="col-4 p-3 m-auto" method="POST">
        <h5 class="text-center alert alert-secondary">EDITAR ETIQUETA</h5>
        <?php
        while ($datos = $sql->fetch_object()) {
        ?>
            <div class="mb-3">
                <label for="modificar_etiqueta_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="modificar_etiqueta_nombre" name="modificar_etiqueta_nombre" value="<?= $datos->nombre ?>">
            </div>
            <div class="mb-3">
                <label for="modificar_etiqueta_color" class="form-label">Color</label>
                <input type="color" class="form-control" id="modificar_etiqueta_color" name="modificar_etiqueta_color" value="<?= $datos->color ?>">
            </div>

            <button type="submit" class="btn btn-primary" name="modificar_btn_submit" value="ok">Guardar</button>
            <a href="../etiquetas.php" class="btn btn-secondary">Volver</a>
        <?php
        }
        ?>
    </form>
</body>

</html>