<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiquetas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <div class="sidebar">
        <h2>Mi Aplicación</h2>
        <ul class="nav flex-column">
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

        <div class="container mt-4">
            <h1 class="text-center mb-4">Etiquetas</h1>

            <!-- Conexiones a la base de datos -->
            <?php
            include "model/conx.php";
            include "controller/registro_etiqueta.php";
            ?>

            <!-- Listado de etiquetas -->
            <div class="table-responsive">
                <?php
                $sql = $conexion->query("SELECT * FROM etiquetas");
                ?>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">Nombre</th>
                            <th class="text-center">Color</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($datos = $sql->fetch_object()) { ?>
                            <tr>
                                <td class="text-start"><?= htmlspecialchars($datos->nombre) ?></td>
                                <td class="text-center">
                                    <span class="label-box" style="background-color:<?= htmlspecialchars($datos->color) ?>">
                                        <?= htmlspecialchars($datos->color) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div>
                                        <!-- Formulario para eliminar etiqueta -->
                                        <form method="POST" action="controller/eliminar_etiqueta.php" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $datos->id ?>">
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta etiqueta?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                        <!-- Botón para editar etiqueta -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal-<?= $datos->id ?>">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal para editar -->
                            <div class="modal fade" id="editModal-<?= $datos->id ?>" tabindex="-1"
                                aria-labelledby="editModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">
                                                Modificar Etiqueta
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="controller/editar_etiqueta.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $datos->id ?>">
                                                <div class="mb-3">
                                                    <label for="nombre-<?= $datos->id ?>" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control"
                                                        id="nombre-<?= $datos->id ?>" name="nombre"
                                                        value="<?= htmlspecialchars($datos->nombre) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="color-<?= $datos->id ?>" class="form-label">Color</label>
                                                    <input type="color" class="form-control"
                                                        id="color-<?= $datos->id ?>" name="color"
                                                        value="<?= htmlspecialchars($datos->color) ?>" required>
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

                <!-- Botón para crear etiqueta -->
                <div class="text-center mb-4">
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
                                        <label for="etiqueta_nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="etiqueta_nombre" name="etiqueta_nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="etiqueta_color" class="form-label">Color</label>
                                        <input type="color" class="form-control" id="etiqueta_color" name="etiqueta_color" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Crear Etiqueta</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
