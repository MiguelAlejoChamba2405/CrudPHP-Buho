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
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-nav .nav-link {
            color: #ffffff !important;
        }
        .navbar-nav .nav-link:hover {
            color: #adb5bd !important;
        }
        .table {
            margin-top: 20px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table thead th {
            background-color: #495057;
            color: #ffffff;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f1f3f5;
        }
        .modal-header {
            background-color: #343a40;
            color: #ffffff;
        }
        .modal-footer {
            background-color: #f8f9fa;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .label-box {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            color: #ffffff;
            font-size: 0.9em;
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Mi Aplicación</a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
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
            </div>
        </nav>

        <div class="container mt-4">
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
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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

                <!-- Botón para crear reunión -->
                <div class="text-center mb-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Crear Reunión</button>
                </div>

                <!-- Modal para crear nueva reunión -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createModalLabel">Crear Nueva Reunión</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre de la Reunión</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="etiquetas" class="form-label">Etiquetas</label>
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
                                        <label for="fecha_inicio" class="form-label">Fecha Inicial</label>
                                        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_fin" class="form-label">Fecha Final</label>
                                        <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Crear Reunión</button>
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
    <!-- Script para evitar reenvío de formulario -->
    <script type="text/javascript" src="Scripts/script.js"></script>
</body>

</html>