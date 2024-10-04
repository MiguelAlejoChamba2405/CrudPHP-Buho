<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuniones</title>
    <!-- CSS -->
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

    <div class="content">
        <h1 class="text-center mb-4">Reuniones</h1>

        <!-- Conexiones a la base de datos -->
        <?php
        include "model/conx.php";
        include "controller/registro_reunion.php";
        ?>

        <!-- Listado de reuniones -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">Campaña</th>
                        <th class="text-center">Etiquetas</th>
                        <th class="text-center">Fecha Inicio</th>
                        <th class="text-center">Fecha Final</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = $conexion->query("SELECT * FROM reuniones");
                    if ($sql) {
                        while ($datos = $sql->fetch_object()) { ?>
                            <tr>
                                <td class="text-start"><?= htmlspecialchars($datos->nombre) ?></td>
                                <td class="text-center">
                                    <?php
                                    $etiquetas_query = $conexion->query("SELECT etiquetas.nombre, etiquetas.color 
                                FROM reunion_etiquetas 
                                JOIN etiquetas ON reunion_etiquetas.etiqueta_id = etiquetas.id 
                                WHERE reunion_etiquetas.reunion_id = $datos->id");
                                    if ($etiquetas_query) {
                                        while ($etiqueta = $etiquetas_query->fetch_object()) {
                                            echo "<span class='label-box' style='background-color: " . htmlspecialchars($etiqueta->color) . "'>" . htmlspecialchars($etiqueta->nombre) . "</span> ";
                                        }
                                    } else {
                                        echo "Sin etiquetas";
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?= htmlspecialchars($datos->fecha_inicio) ?></td>
                                <td class="text-center"><?= htmlspecialchars($datos->fecha_fin) ?></td>
                                <td class="text-end">
                                    <form method="POST" action="controller/eliminar_reunion.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $datos->id ?>">
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta reunión?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal-<?= $datos->id ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <!-- Botón para ver contactos -->
                                    <button class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#contactModal-<?= $datos->id ?>">
                                        Ver Contactos
                                    </button>
                                    <!-- Botón para mensaje -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#messageModal-<?= $datos->id ?>">
                                        Mensaje
                                    </button>

                                    <!-- Modal para enviar mensaje -->
                                    <div class="modal fade" id="messageModal-<?= $datos->id ?>" tabindex="-1"
                                        aria-labelledby="messageModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="messageModalLabel-<?= $datos->id ?>">Enviar
                                                        Mensaje</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="reuniones.php" method="POST">
                                                        <input type="hidden" name="reunion_id" value="<?= $datos->id ?>">
                                                        <div class="mb-3">
                                                            <label for="mensaje" class="form-label">Mensaje</label>
                                                            <textarea class="form-control" id="mensaje" name="mensaje"
                                                                rows="4" required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Listo</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal para ver contactos -->
                            <div class="modal fade" id="contactModal-<?= $datos->id ?>" tabindex="-1"
                                aria-labelledby="contactModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="contactModalLabel-<?= $datos->id ?>">Contactos para
                                                la Reunión: <?= htmlspecialchars($datos->nombre) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            // Obtener los IDs de las etiquetas asociadas a la reunión
                                            $etiquetas_ids_query = $conexion->query("
                                    SELECT etiqueta_id 
                                    FROM reunion_etiquetas 
                                    WHERE reunion_id = $datos->id
                                ");

                                            $usuario_ids = [];

                                            // Para cada etiqueta obtenida, encontrar los usuarios asociados
                                            while ($etiqueta_id_obj = $etiquetas_ids_query->fetch_object()) {
                                                $etiqueta_id = $etiqueta_id_obj->etiqueta_id;

                                                $usuarios_query = $conexion->query("SELECT u.id, u.nombre 
                                        FROM usuario_etiquetas ue
                                        JOIN usuarios u ON ue.usuario_id = u.id
                                        WHERE ue.etiqueta_id = $etiqueta_id
                                    ");

                                                if ($usuarios_query) {
                                                    while ($usuario = $usuarios_query->fetch_object()) {
                                                        if (!in_array($usuario->id, $usuario_ids)) {
                                                            $usuario_ids[] = $usuario->id;
                                                            echo "<div>" . htmlspecialchars($usuario->nombre) . "</div>";
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para editar la reunión -->
                            <div class="modal fade" id="editModal-<?= $datos->id ?>" tabindex="-1"
                                aria-labelledby="editModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">Modificar Reunión
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="controller/editar_reunion.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $datos->id ?>">
                                                <div class="mb-3">
                                                    <label for="nombre" class="form-label">Nombre de la Reunión</label>
                                                    <input type="text" class="form-control" name="nombre"
                                                        value="<?= htmlspecialchars($datos->nombre) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                                    <input type="date" class="form-control" name="fecha_inicio"
                                                        value="<?= htmlspecialchars($datos->fecha_inicio) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fecha_fin" class="form-label">Fecha Final</label>
                                                    <input type="date" class="form-control" name="fecha_fin"
                                                        value="<?= htmlspecialchars($datos->fecha_fin) ?>" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No hay reuniones registradas</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>