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
    <div class="container-fluid row">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="usuarios.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="etiquetas.php">Etiquetas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reuniones.php">Reuniones</a>
                    </li>
                </ul>
            </div>
        </nav>
        <h1 class="text-center">ETIQUETAS</h1>

        <?php
        include "model/conx.php";
        include "controller/registro_etiqueta.php";
        ?>
        <!-- Contenedor para la tabla de etiquetas -->
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-star" scope="col">NOMBRE</th>
                        <th class="text-center" scope="col">COLOR</th>
                        <th class="text-end" scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "model/conx.php";
                    $sql = $conexion->query("SELECT * FROM etiquetas");
                    while ($datos = $sql->fetch_object()) { ?>
                        <tr>
                            <td class="text-star"><?= $datos->nombre ?></td>
                            <td class="text-center"><?= $datos->color ?></td>
                            <td class="text-end">
                                <!-- Botón para eliminar la etiqueta -->
                                <form method="POST" action="controller/eliminar_etiqueta.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $datos->id ?>">
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta etiqueta?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                <!-- Botón para editar la etiqueta -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal-<?= $datos->id ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal para editar etiqueta -->
                        <div class="modal fade" id="editModal-<?= $datos->id ?>" tabindex="-1"
                            aria-labelledby="editModalLabel-<?= $datos->id ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">Modificar Etiqueta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="controller/editar_etiqueta.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $datos->id ?>">
                                            <div class="mb-3">
                                                <label for="nombre-<?= $datos->id ?>" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombre-<?= $datos->id ?>" name="nombre" value="<?= $datos->nombre ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="color-<?= $datos->id ?>" class="form-label">Color</label>
                                                <input type="color" class="form-control" id="color-<?= $datos->id ?>" name="color" value="<?= $datos->color ?>">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </tbody>


            </table>
            <div class="col.auto text-center">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Crear Etiqueta</button>
            </div>
            <!-- Modal para crear nueva etiqueta -->
            <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Crear Nueva Etiqueta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="etiquetas_nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="etiquetas_nombre" name="etiquetas_nombre">
                                </div>
                                <div class="mb-3">
                                    <label for="etiquetas_color" class="form-label">Color</label>
                                    <input type="color" class="form-control" id="etiquetas_color" name="etiquetas_color">
                                </div>
                                <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Crear Etiqueta</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                            </form>
                        </div>
                    </div>
                </div>

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