<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reuniones</title>
    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <h1 class="text-center">REUNIONES</h1>

        <?php
        include "model/conx.php";
        include "controller/registro_reunion.php";
        ?>
        <!-- Listado de reuniones -->
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-star" scope="col">CAMPAÑA</th>
                        <th class="text-center" scope="col">ETIQUETAS</th>
                        <th class="text-center" scope="col">FECHA INICIO</th>
                        <th class="text-center" scope="col">FECHA FINAL</th>
                        <th class="text-end" scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = $conexion->query("SELECT * FROM reuniones");
                    if ($sql) {
                        while ($datos = $sql->fetch_object()) { ?>
                            <tr>
                                <td class="text-star"><?= htmlspecialchars($datos->nombre) ?></td>
                                <td class="text-center">
                                    <?php
                                    $etiquetas_query = $conexion->query("SELECT etiquetas.nombre 
                                    FROM reunion_etiquetas 
                                    JOIN etiquetas ON reunion_etiquetas.etiqueta_id = etiquetas.id 
                                    WHERE reunion_etiquetas.reunion_id = $datos->id");
                                    if ($etiquetas_query) {
                                        while ($etiqueta = $etiquetas_query->fetch_object()) {
                                            echo htmlspecialchars($etiqueta->nombre) . ' ';
                                        }
                                    } else {
                                        echo "Sin etiquetas";
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?= htmlspecialchars($datos->fecha_inicio) ?></td>
                                <td class="text-center"><?= htmlspecialchars($datos->fecha_fin) ?></td>
                                <td class="text-end">
                                    <!-- Botón para eliminar la reunión -->
                                    <form method="POST" action="controller/eliminar_reunion.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $datos->id ?>">
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta reunión?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                    <!-- Botón que activa el modal para modificar la reunión -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal-<?= $datos->id ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal para editar la reunión -->
                            <div class="modal fade" id="editModal-<?= $datos->id ?>" tabindex="-1"
                                aria-labelledby="editModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">Modificar Reunión</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="controller/editar_reunion.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $datos->id ?>">
                                                <div class="mb-3">
                                                    <label for="nombre-<?= $datos->id ?>" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="nombre-<?= $datos->id ?>"
                                                        name="nombre" value="<?= htmlspecialchars($datos->nombre) ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="etiquetas-<?= $datos->id ?>" class="form-label">Etiquetas</label>
                                                    <select class="form-select" id="etiquetas-<?= $datos->id ?>"
                                                        name="etiquetas[]" multiple>
                                                        <?php
                                                        $etiquetas = $conexion->query("SELECT * FROM etiquetas");
                                                        $selected_etiquetas = [];
                                                        $etiquetas_seleccionadas = $conexion->query("SELECT etiqueta_id FROM reunion_etiquetas WHERE reunion_id = $datos->id");
                                                        while ($etiqueta_selec = $etiquetas_seleccionadas->fetch_object()) {
                                                            $selected_etiquetas[] = $etiqueta_selec->etiqueta_id;
                                                        }
                                                        while ($etiqueta = $etiquetas->fetch_object()) { ?>
                                                            <option value="<?= $etiqueta->id ?>"
                                                                <?php if (in_array($etiqueta->id, $selected_etiquetas)) echo 'selected'; ?>>
                                                                <?= htmlspecialchars($etiqueta->nombre) ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fecha_inicio-<?= $datos->id ?>" class="form-label">Fecha Inicio</label>
                                                    <input type="datetime-local" class="form-control"
                                                        id="fecha_inicio-<?= $datos->id ?>" name="fecha_inicio"
                                                        value="<?= date('Y-m-d\TH:i', strtotime($datos->fecha_inicio)) ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fecha_fin-<?= $datos->id ?>" class="form-label">Fecha Final</label>
                                                    <input type="datetime-local" class="form-control"
                                                        id="fecha_fin-<?= $datos->id ?>" name="fecha_fin"
                                                        value="<?= date('Y-m-d\TH:i', strtotime($datos->fecha_fin)) ?>">
                                                </div>
                                                <button type="submit" class="btn btn-primary" >Guardar Cambios</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Salir</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } else {
                        echo "<tr><td colspan='5'>No se pudieron cargar las reuniones</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="col.auto text-center">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Crear Reunion</button>
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
                                    <label for="nombre" class="form-label">REUNIONES</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="etiquetas" class="form-label">ETIQUETAS</label>
                                    <select class="form-select" id="etiquetas" name="etiquetas[]" multiple required>
                                        <option value="" disabled>Selecciona una o más etiquetas</option>
                                        <?php
                                        $sql = $conexion->query("SELECT * FROM etiquetas");
                                        if ($sql) {
                                            while ($etiqueta = $sql->fetch_object()) { ?>
                                                <option value="<?= $etiqueta->id ?>"><?= $etiqueta->nombre ?> (<?= $etiqueta->color ?>)</option>
                                        <?php }
                                        } else {
                                            echo "<option disabled>Error al cargar etiquetas</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fecha_inicio" class="form-label">FECHA INICIAL</label>
                                    <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fecha_fin" class="form-label">FECHA FINAL</label>
                                    <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required>
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

    <!-- JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- script evitar reenvio de formulario -->
    <script type="text/javascript" src="Scripts/script.js"></script>
</body>

</html>