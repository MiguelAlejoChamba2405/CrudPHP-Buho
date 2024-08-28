<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiquetas</title>
    <!--link bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c5ebb6dec6.js" crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center p-3">CRUD PHP</h1>

    <div class="container-fluid row ">
        <?php
        include "model/conx.php";
        include "controller/registro_etiqueta.php";
        ?>
        <form class="col-4" method="POST">
            <h3 class="text-center text-secondary">Registro de Etiquetas</h3>

            <div class="mb-3">
                <label for="etiquetas_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="etiquetas_nombre" name="etiquetas_nombre">
            </div>
            <div class="mb-3">
                <label for="etiquetas_color" class="form-label">Color</label>
                <input type="color" class="form-control" id="etiquetas_color" name="etiquetas_color">
            </div>
            <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Guardar</button>

        </form>
        <div class="col-8 p-4">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">NOMBRE</th>
                        <th scope="col">COLOR</th>
                        <th scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "model/conx.php";
                    $sql = $conexion->query("SELECT * FROM etiquetas");
                    while ($datos = $sql->fetch_object()) { ?>
                        <tr>
                            <td><?= $datos->nombre ?></td>
                            <td><?= $datos->color ?></td>
                            <td>
                                <!-- Botón para eliminar el usuario -->
                                <form method="POST" action="controller/eliminar_etiqueta.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $datos->id ?>">
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar a esta persona?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                <!-- Botón que activa el modal para modificar la etiqueta -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal-<?= $datos->id ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="editModal-<?= $datos->id ?>" tabindex="-1"
                            aria-labelledby="editModalLabel-<?= $datos->id ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">Modificar Etiqueta
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="controller/editar_etiqueta.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $datos->id ?>">
                                            <div class="mb-3">
                                                <label for="nombre-<?= $datos->id ?>" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombre-<?= $datos->id ?>"
                                                    name="nombre" value="<?= $datos->nombre ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="color-<?= $datos->id ?>" class="form-label">Color</label>
                                                <input type="color" class="form-control" id="color-<?= $datos->id ?>"
                                                    name="color" value="<?= $datos->color ?>">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Salir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
            <a href="usuarios.php" class="btn btn-primary">Ir a Usuarios</a>
        </div>
    </div>
    <!--script bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!--script evitar reenvio de formulario-->
    <script type="text/javascript" src="Scripts/script.js"></script>
</body>

</html>