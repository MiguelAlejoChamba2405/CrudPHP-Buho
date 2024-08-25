<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiquetas</title>
    <!--link bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c5ebb6dec6.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="container-fluid row ">
        <?php
        include "../MODELO/conexion.php";
        include "../CONTROLADOR/registro_etiqueta.php";


        ?>
        <form class="col-4" method="POST">
            <div class="mb-3">
                <label for="etiqueta_nombre" class="form-label">Nombre</label>

                <input type="text" class="form-control" id="etiqueta_nombre" name="etiqueta_nombre">

            </div>
            <div class="mb-3">
                <label for="etiqueta_color" class="form-label">Color</label>
                <input type="color" class="form-control" id="etiqueta_color" name="etiqueta_color">
            </div>

            <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Guardar</button>
        </form>
        <div class="col-8 p-4">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">NOMBRE</th>
                        <th scope="col">COLOR</th>
                        <th scope="col">ACCIONES</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../MODELO/conexion.php";
                    $sql = $conn->query("select * from etiquetas");
                    while ($datos = $sql->fetch_object()) {
                    ?>
                        <tr>
                            <th scope="row"><?= $datos->id ?></th>
                            <td><?= $datos->nombre ?></td>
                            <td><?= $datos->color ?></td>
                            <td>
                                <a href="../CONTROLADOR/editar_etiqueta.php?id=<?= $datos->id ?>" class="btn btn-small btn-warning"><i class="fa-solid fa-pen"></i></a>
                                <a href="../CONTROLADOR/eliminar_etiqueta.php?id=<?php $datos->id ?>" class="btn btn-small btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>



    <!--script bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>