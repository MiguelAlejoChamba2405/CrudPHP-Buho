<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP y MYSQL - BUHO</title>
    <!-- Css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <h1 class="text-center p-3">CRUD PHP</h1>

    <div class="container-fluid row">
        <?php
        include "model/conx.php";
        include "controller/registro_personal.php";
        ?>
        <form class="col-4 p-3" method="POST">
            <h3 class="text-center text-secondary">Registro de Personas</h3>
            <div class="mb-3">
                <label for="usuario_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="usuario_nombre" name="usuario_nombre" required>
            </div>
            <div class="mb-3">
                <label for="usuario_numero" class="form-label">Número</label>
                <input type="number" class="form-control" id="usuario_numero" name="usuario_numero" required>
            </div>
            <div class="mb-3">
                <label for="etiqueta_id" class="form-label">Etiquetas</label>
                <select class="form-select" id="etiqueta_id" name="etiqueta_id[]" multiple required>
                    <option value="" disabled>Selecciona una o más etiquetas</option>
                    <?php
                    $sql = $conexion->query("SELECT * FROM etiquetas");
                    while ($etiqueta = $sql->fetch_object()) { ?>
                        <option value="<?= $etiqueta->id ?>"><?= $etiqueta->nombre ?> (<?= $etiqueta->color ?>)</option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="btnregistrar" value="ok">Registrar</button>

        </form>
        <div class="col-8 p-4">
            <?php
            include "model/conx.php";
            $sql = $conexion->query("SELECT usuarios.id, usuarios.nombre, usuarios.numero, GROUP_CONCAT(etiquetas.nombre SEPARATOR ', ') AS etiqueta_nombre, GROUP_CONCAT(etiquetas.color SEPARATOR ', ') AS colores
    FROM usuarios 
    LEFT JOIN usuario_etiquetas ON usuarios.id = usuario_etiquetas.usuario_id
    LEFT JOIN etiquetas ON usuario_etiquetas.etiqueta_id = etiquetas.id
    GROUP BY usuarios.id");

            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">NOMBRE</th>
                        <th scope="col">NÚMERO</th>
                        <th scope="col">ETIQUETA</th>
                        <th scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql->data_seek(0); // Reinicia el puntero de resultados
                    while ($datos = $sql->fetch_object()) { ?>
                        <tr>
                            <td><?= $datos->nombre ?></td>
                            <td><?= $datos->numero ?></td>
                            <td><?= $datos->etiqueta_nombre ?> <span style="color:<?= $datos->colores ?>">&#9679;</span>
                            </td>
                            </td>

                            <td>
                                <!-- Botón para eliminar el usuario -->
                                <form method="POST" action="controller/eliminar_usuario.php" style="display:inline;">
                                    <input type="hidden" name="id_usuario" value="<?= $datos->id ?>">
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar a esta persona?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                <!-- Botón que activa el modal para modificar la etiqueta -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal-<?= $datos->id ?>"
                                    data-nombre="<?= $datos->etiqueta_nombre ?>" data-color="<?= $datos->color ?>"
                                    data-numero="<?= $datos->numero ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="editModal-<?= $datos->id ?>" tabindex="-1"
                                    aria-labelledby="editModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">Modificar
                                                    Etiqueta</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="controller/editar_usuario.php" method="POST">
                                                    <input type="hidden" name="id" value="<?= $datos->id ?>">
                                                    <div class="mb-3">
                                                        <label for="nombre-<?= $datos->id ?>"
                                                            class="form-label">Nombre</label>
                                                        <input type="text" class="form-control"
                                                            id="nombre-<?= $datos->id ?>" name="nombre"
                                                            value="<?= $datos->nombre ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="numero-<?= $datos->id ?>"
                                                            class="form-label">Número</label>
                                                        <input type="number" class="form-control"
                                                            id="numero-<?= $datos->id ?>" name="numero"
                                                            value="<?= $datos->numero ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="etiqueta_id" class="form-label">Etiquetas</label>
                                                        <select class="form-select" id="etiqueta_id" name="etiqueta_id[]"
                                                            multiple required>
                                                            <option value="" disabled>Selecciona una o más etiquetas
                                                            </option>
                                                            <?php
                                                            $etiquetasSql = $conexion->query("SELECT * FROM etiquetas");
                                                            $usuario_etiquetas = []; // Recupera las etiquetas del usuario actual desde la base de datos
                                                            while ($etiqueta = $etiquetasSql->fetch_object()) {
                                                                $selected = in_array($etiqueta->id, $usuario_etiquetas) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?= $etiqueta->id ?>" <?= $selected ?>>
                                                                    <?= $etiqueta->nombre ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Salir</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="etiquetas.php" class="btn btn-primary">Ir a Etiquetas</a>
        </div>
    </div>



    <!-- JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    <!--script evitar reenvio de formulario-->
    <script type="text/javascript" src="Scripts/script.js"></script>

</body>

</html>