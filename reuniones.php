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
    <h1 class="text-center p-3">Reuniones</h1>

    <div class="container-fluid row">
        <?php
        include "model/conx.php";
        include "controller/registro_reunion.php"; // Asegúrate de crear este controlador
        ?>
        <form class="col-4" method="POST">
            <h3 class="text-center text-secondary">Registro de Reuniones</h3>

            <div class="mb-3">
                <label for="nombre" class="form-label">CAMPAÑA</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="etiquetas" class="form-label">ETIQUETAS</label>
                <select class="form-select" id="etiquetas" name="etiquetas[]" multiple required>
                    <option value="" disabled>Selecciona una o más etiquetas</option>
                    <?php
                    $sql = $conexion->query("SELECT * FROM etiquetas");
                    while ($etiqueta = $sql->fetch_object()) { ?>
                        <option value="<?= $etiqueta->id ?>"><?= $etiqueta->nombre ?> (<?= $etiqueta->color ?>)</option>
                    <?php } ?>
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
            <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Guardar</button>

        </form>
        <div class="col-8 p-4">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">CAMPAÑA</th>
                        <th scope="col">ETIQUETAS</th>
                        <th scope="col">FECHA INICIO</th>
                        <th scope="col">FECHA FINAL</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "model/conx.php";
                    $sql = $conexion->query("SELECT * FROM reuniones");
                    while ($datos = $sql->fetch_object()) { ?>
                        <tr>
                            <td><?= $datos->nombre ?></td>
                            <td>
                                <?php
                                    $etiquetas_query = $conexion->query("SELECT etiquetas.nombre 
                                FROM reunion_etiquetas 
                                JOIN etiquetas ON reunion_etiquetas.etiqueta_id = etiquetas.id 
                                WHERE reunion_etiquetas.reunion_id = $datos->id");
                                    while ($etiqueta = $etiquetas_query->fetch_object()) {
                                        echo $etiqueta->nombre . ' ';
                                    }
                                    ?>
                            </td>
                            <td><?= $datos->fecha_inicio ?></td>
                            <td><?= $datos->fecha_fin ?></td>
                            <td>
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

                        <!-- Modal -->
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
                                                    name="nombre" value="<?= $datos->nombre ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="etiquetas-<?= $datos->id ?>" class="form-label">Etiquetas</label>
                                                <select class="form-select" id="etiquetas-<?= $datos->id ?>"
                                                    name="etiquetas[]" multiple>
                                                    <?php
                                                    $etiquetas = $conexion->query("SELECT * FROM etiquetas");
                                                    while ($etiqueta = $etiquetas->fetch_object()) { ?>
                                                        <option value="<?= $etiqueta->id ?>" 
                                                            <?php if (in_array($etiqueta->id, explode(',', $datos->etiquetas))) echo 'selected'; ?>>
                                                            <?= $etiqueta->nombre ?>
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
            <a href="etiquetas.php" class="btn btn-primary">Ir a Etiquetas</a>
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