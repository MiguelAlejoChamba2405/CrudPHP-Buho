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
                    $sql = $conexion->query(query: "SELECT * FROM reuniones");
                    if ($sql) {
                        while ($datos = $sql->fetch_object()) { ?>
                            <tr>
                                <td class="text-start"><?= htmlspecialchars(string: $datos->nombre) ?></td>
                                <td class="text-center">
                                    <?php
                                    $etiquetas_query = $conexion->query(query: "SELECT etiquetas.nombre, etiquetas.color 
                                FROM reunion_etiquetas 
                                JOIN etiquetas ON reunion_etiquetas.etiqueta_id = etiquetas.id 
                                WHERE reunion_etiquetas.reunion_id = $datos->id");
                                    if ($etiquetas_query) {
                                        while ($etiqueta = $etiquetas_query->fetch_object()) {
                                            echo "<span class='label-box' style='background-color: " . htmlspecialchars(string: $etiqueta->color) . "'>" . htmlspecialchars(string: $etiqueta->nombre) . "</span> ";
                                        }
                                    } else {
                                        echo "Sin etiquetas";
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?= htmlspecialchars(string: $datos->fecha_inicio) ?></td>
                                <td class="text-center"><?= htmlspecialchars(string: $datos->fecha_fin) ?></td>
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
                                </td>
                            </tr>

                            <!-- Modal para ver contactos -->
                            <div class="modal fade" id="contactModal-<?= $datos->id ?>" tabindex="-1"
                                aria-labelledby="contactModalLabel-<?= $datos->id ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="contactModalLabel-<?= $datos->id ?>">Contactos para la
                                                Reunión: <?= htmlspecialchars($datos->nombre) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            // Obtener los IDs de los usuarios asociados a la reunión
                                            $usuarios_query = $conexion->query("
                    SELECT u.id, u.nombre 
                    FROM reunion_usuarios ru
                    JOIN usuarios u ON ru.usuario_id = u.id
                    WHERE ru.reunion_id = {$datos->id}
                ");

                                            // Verifica si se obtuvieron usuarios
                                            if ($usuarios_query && $usuarios_query->num_rows > 0) {
                                                while ($usuario = $usuarios_query->fetch_object()) {
                                                    echo "<div>" . htmlspecialchars($usuario->nombre) . "</div>";
                                                }
                                            } else {
                                                echo "<div>No hay contactos guardados para esta reunión.</div>";
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
                                            <h5 class="modal-title" id="editModalLabel-<?= $datos->id ?>">Modificar Reunión</h5>
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
                                                <div class="mb-3">
                                                    <label for="etiquetas" class="form-label">Etiquetas Asociadas</label>
                                                    <select name="etiquetas[]" class="form-control" multiple>
                                                        <?php
                                                        // Obtener etiquetas existentes para mostrarlas en el select
                                                        $etiquetas_query = $conexion->query("SELECT id, nombre FROM etiquetas");
                                                        $etiquetas_asociadas = []; // Array para almacenar etiquetas asociadas a la reunión
                                                
                                                        // Obtener etiquetas asociadas a esta reunión
                                                        $etiquetas_reunion_query = $conexion->query("SELECT etiqueta_id FROM reunion_etiquetas WHERE reunion_id = {$datos->id}");
                                                        while ($etiqueta = $etiquetas_reunion_query->fetch_object()) {
                                                            $etiquetas_asociadas[] = $etiqueta->etiqueta_id;
                                                        }

                                                        while ($etiqueta = $etiquetas_query->fetch_object()) {
                                                            $selected = in_array($etiqueta->id, $etiquetas_asociadas) ? 'selected' : '';
                                                            echo "<option value='{$etiqueta->id}' $selected>" . htmlspecialchars($etiqueta->nombre) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="integrantes" class="form-label">Integrantes</label>
                                                    <select name="integrantes[]" class="form-control" multiple>
                                                        <?php
                                                        // Obtener usuarios existentes para mostrarlos en el select
                                                        $usuarios_query = $conexion->query("SELECT id, nombre FROM usuarios");
                                                        $integrantes_asociados = []; // Array para almacenar integrantes asociados
                                                
                                                        // Obtener integrantes asociados a esta reunión
                                                        $integrantes_reunion_query = $conexion->query("SELECT usuario_id FROM reunion_usuarios WHERE reunion_id = {$datos->id}");
                                                        while ($integrante = $integrantes_reunion_query->fetch_object()) {
                                                            $integrantes_asociados[] = $integrante->usuario_id;
                                                        }

                                                        while ($usuario = $usuarios_query->fetch_object()) {
                                                            $selected = in_array($usuario->id, $integrantes_asociados) ? 'selected' : '';
                                                            echo "<option value='{$usuario->id}' $selected>" . htmlspecialchars($usuario->nombre) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
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
                    <!-- Botón para crear reunión -->
                    <div class="text-center mb-4">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Crear
                            Reunión</button>
                    </div>

                    <!-- Modal para crear nueva reunión -->
                    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createModalLabel">Crear Nueva Reunión</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="etiquetas" class="form-label">Etiquetas</label>
                                            <select class="form-select" id="etiquetas" name="etiquetas[]" multiple
                                                onchange="filtrarUsuarios()" required>
                                                <?php
                                                $etiquetas = $conexion->query("SELECT * FROM etiquetas");
                                                if ($etiquetas) {
                                                    while ($etiqueta = $etiquetas->fetch_object()) { ?>
                                                        <option value="<?= $etiqueta->id ?>">
                                                            <?= htmlspecialchars($etiqueta->nombre) ?> (<?= $etiqueta->color ?>)
                                                        </option>
                                                    <?php }
                                                } else {
                                                    echo "<option disabled>Error al cargar etiquetas</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                        // Obtener los usuarios con sus etiquetas
                                        $query = "
    SELECT u.id AS usuario_id, u.nombre AS usuario_nombre, GROUP_CONCAT(e.nombre SEPARATOR ', ') AS etiquetas
    FROM usuarios u
    LEFT JOIN usuario_etiquetas ue ON u.id = ue.usuario_id
    LEFT JOIN etiquetas e ON ue.etiqueta_id = e.id
    GROUP BY u.id
";
                                        $usuarios = $conexion->query($query);
                                        ?>

                                        <div class="mb-3">
                                            <label for="integrantes" class="form-label">Miembros de la reunión</label>
                                            <select class="form-select" id="integrantes" name="integrantes[]" multiple
                                                required>
                                                <?php
                                                if ($usuarios) {
                                                    while ($usuario = $usuarios->fetch_object()) { ?>
                                                        <option value="<?= $usuario->usuario_id ?>">
                                                            <?= htmlspecialchars($usuario->usuario_nombre) ?>
                                                            <?= !empty($usuario->etiquetas) ? "($usuario->etiquetas)" : '' ?>
                                                        </option>
                                                    <?php }
                                                } else {
                                                    echo "<option disabled>Error al cargar usuarios</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fecha_inicio" class="form-label">Fecha Inicial</label>
                                            <input type="datetime-local" class="form-control" id="fecha_inicio"
                                                name="fecha_inicio" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fecha_fin" class="form-label">Fecha Final</label>
                                            <input type="datetime-local" class="form-control" id="fecha_fin"
                                                name="fecha_fin" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Crear
                                            Reunión</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Salir</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

        </div>


        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Script para evitar reenvío de formulario -->
        <script type="text/javascript" src="Scripts/script.js"></script>
</body>

</html>