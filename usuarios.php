<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid row">
        <!-- Navbar -->
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

        <h1 class="text-center">USUARIOS</h1>

        <!-- Conexiones a la base de datos -->
        <?php
        include "model/conx.php";
        include "controller/registro_personal.php";
        ?>

        <!-- Listado de usuarios -->
        <div class="col">
            <?php
            $sql = $conexion->query("
                SELECT usuarios.id, usuarios.nombre, usuarios.numero, 
                GROUP_CONCAT(etiquetas.nombre SEPARATOR ', ') AS etiqueta_nombre, 
                GROUP_CONCAT(COALESCE(etiquetas.color, '') SEPARATOR ', ') AS colores
                FROM usuarios 
                LEFT JOIN usuario_etiquetas ON usuarios.id = usuario_etiquetas.usuario_id
                LEFT JOIN etiquetas ON usuario_etiquetas.etiqueta_id = etiquetas.id
                GROUP BY usuarios.id
            ");
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-start" scope="col">NOMBRE</th>
                        <th class="text-center" scope="col">NÚMERO</th>
                        <th class="text-center" scope="col">ETIQUETA</th>
                        <th class="text-end" scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($datos = $sql->fetch_object()) { ?>
                        <tr>
                            <td class="text-start"><?= htmlspecialchars($datos->nombre) ?></td>
                            <td class="text-center"><?= htmlspecialchars($datos->numero) ?></td>
                            <td class="text-center">
                                <?= htmlspecialchars($datos->etiqueta_nombre) ?>
                                <?php if (!empty($datos->colores)) : ?>
                                    <span style="color:<?= htmlspecialchars($datos->colores) ?>">&#9679;</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div>
                                    <!-- Formulario para eliminar usuario -->
                                    <form method="POST" action="controller/eliminar_usuario.php" style="display:inline;">
                                        <input type="hidden" name="id_usuario" value="<?= $datos->id ?>">
                                        <button type="submit" class="btn btn-danger "
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar a esta persona?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                    <!-- Botón para editar usuario -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal-<?= $datos->id ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                </div>
                            </td>
                            <!-- Modal para editar -->
                            <div class="modal fade" id="editModal-<?= $datos->id ?>" tabindex="-1"
                                    aria-labelledby="editModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">
                                                    Modificar Usuario
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="controller/editar_usuario.php" method="POST">
                                                    <input type="hidden" name="id" value="<?= $datos->id ?>">
                                                    <div class="mb-3">
                                                        <label for="nombre-<?= $datos->id ?>" class="form-label">Nombre</label>
                                                        <input type="text" class="form-control"
                                                            id="nombre-<?= $datos->id ?>" name="nombre"
                                                            value="<?= htmlspecialchars($datos->nombre) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="numero-<?= $datos->id ?>" class="form-label">Número</label>
                                                        <input type="number" class="form-control"
                                                            id="numero-<?= $datos->id ?>" name="numero"
                                                            value="<?= htmlspecialchars($datos->numero) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="etiqueta_id_<?= $datos->id ?>" class="form-label">Etiquetas</label>
                                                        <select class="form-select" id="etiqueta_id_<?= $datos->id ?>" name="etiqueta_id[]"
                                                            multiple required>
                                                            <option value="" disabled>Selecciona una o más etiquetas</option>
                                                            <?php
                                                            $etiquetasSql = $conexion->query("SELECT * FROM etiquetas");
                                                            // Crear array de etiquetas del usuario actual
                                                            $usuario_etiquetas = explode(", ", $datos->etiqueta_nombre);
                                                            while ($etiqueta = $etiquetasSql->fetch_object()) {
                                                                $selected = in_array($etiqueta->nombre, $usuario_etiquetas) ? 'selected' : '';
                                                            ?>
                                                                <option value="<?= $etiqueta->id ?>" <?= $selected ?>>
                                                                    <?= htmlspecialchars($etiqueta->nombre) ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Botón para crear usuario -->
            <div class="col.auto text-center">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Crear Usuario</button>
            </div>

            <!-- Modal para crear nuevo usuario -->
            <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Crear Nuevo Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
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
                                            <option value="<?= $etiqueta->id ?>"><?= htmlspecialchars($etiqueta->nombre) ?> (<?= htmlspecialchars($etiqueta->color) ?>)</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Guardar Usuario</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
     <!--script evitar reenvio de formulario-->
     <script type="text/javascript" src="Scripts/script.js"></script>
</body>

</html>