<!DOCTYPE html>
<html lang="es">

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
        <form class="col-4 p-3" method="POST">
            <h3 class="text-center text-secondary">Registro de Personas</h3>
            <?php
            include "model/conx.php";
            include "controller/registro_personal.php";
            ?>
            <div class="mb-3">
                <label for="usuario_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="usuario_nombre" name="usuario_nombre" required>
            </div>
            <div class="mb-3">
                <label for="usuario_numero" class="form-label">Número</label>
                <input type="number" class="form-control" id="usuario_numero" name="usuario_numero" required>
            </div>
            <div class="mb-3">
                <label for="etiqueta_id" class="form-label">Etiqueta</label>
                <select class="form-select" id="etiqueta_id" name="etiqueta_id" required>
                    <option value="" selected disabled>Selecciona una etiqueta</option>
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
            <table class="table">
                <thead class="bg-info">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">NOMBRES</th>
                        <th scope="col">NÚMERO</th>
                        <th scope="col">ETIQUETA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = $conexion->query("SELECT usuarios.id, usuarios.nombre, usuarios.numero, etiquetas.nombre AS etiqueta_nombre, etiquetas.color 
                                             FROM usuarios 
                                             LEFT JOIN etiquetas ON usuarios.etiquetas = etiquetas.id");
                    while ($datos = $sql->fetch_object()) { ?>
                        <tr>
                            <td><?= $datos->id ?></td>
                            <td><?= $datos->nombre ?></td>
                            <td><?= $datos->numero ?></td>
                            <td><?= $datos->etiqueta_nombre ?> <span style="color:<?= $datos->color ?>">&#9679;</span></td>
                            <td>
                                <!-- Botón para eliminar el usuario -->
                                <form method="POST" action="controller/eliminar_usuario.php" style="display:inline;">
                                    <input type="hidden" name="id_usuario" value="<?= $datos->id ?>">
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar a esta persona?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                <!-- Botón que activa el modal para modificar la persona -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modificarPersonasModal" data-id="<?= $datos->id ?>"
                                    data-nombre="<?= $datos->nombre ?>" data-numero="<?= $datos->numero ?>"
                                    data-etiqueta="<?= $datos->etiquetas ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="etiquetas.php" class="btn btn-primary">Ir a Etiquetas</a>
        </div>
    </div>

    <!-- Modal para modificar personas -->
    <div class="modal fade" id="modificarPersonasModal" tabindex="-1" aria-labelledby="modificarPersonasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarPersonasModalLabel">Modificar Personas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modificarPersonasForm" method="POST">
                        <div class="mb-3">
                            <label for="m_usuario_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="m_usuario_nombre" name="m_usuario_nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="m_usuario_numero" class="form-label">Número</label>
                            <input type="number" class="form-control" id="m_usuario_numero" name="m_usuario_numero" required>
                        </div>
                        <div class="mb-3">
                            <label for="m_etiqueta_id" class="form-label">Etiqueta</label>
                            <select class="form-select" id="m_etiqueta_id" name="m_etiqueta_id" required>
                                <?php
                                $sql = $conexion->query("SELECT * FROM etiquetas");
                                while ($etiqueta = $sql->fetch_object()) { ?>
                                    <option value="<?= $etiqueta->id ?>"><?= $etiqueta->nombre ?> (<?= $etiqueta->color ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                        <input type="hidden" id="id_usuario" name="id_usuario">
                        <button type="submit" class="btn btn-primary" name="btnModificar" value="ok">Modificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- Script para manejar el envío del formulario de modificación -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modificarModal = document.getElementById('modificarPersonasModal');
            modificarModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                // Extraer información de los atributos data-
                var id = button.getAttribute('data-id');
                var nombre = button.getAttribute('data-nombre');
                var numero = button.getAttribute('data-numero');
                var etiqueta = button.getAttribute('data-etiqueta');

                // Actualizar los valores del formulario dentro del modal
                var modalForm = modificarModal.querySelector('#modificarPersonasForm');
                modalForm.querySelector('#m_usuario_nombre').value = nombre;
                modalForm.querySelector('#m_usuario_numero').value = numero;
                modalForm.querySelector('#m_etiqueta_id').value = etiqueta;

                // Añadir el campo oculto con el ID del usuario
                var inputID = modalForm.querySelector('#id_usuario');
                if (!inputID) {
                    inputID = document.createElement('input');
                    inputID.type = 'hidden';
                    inputID.name = 'id_usuario';
                    inputID.id = 'id_usuario';
                    modalForm.appendChild(inputID);
                }
                inputID.value = id;
            });

            // Mostrar el modal de éxito si se pasa el parámetro 'actualizado'
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('actualizado')) {
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                // Limpiar el parámetro después de mostrar el modal
                window.history.replaceState({}, '', window.location.pathname);
            }
        });

        document.getElementById('modificarPersonasForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var form = this;

            // Mostrar el modal de éxito al enviar el formulario de modificación
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Luego de mostrar el modal, enviar el formulario
            form.submit();
        });
    </script>
        <script type="text/javascript" src="script.js"></script>

</body>

</html>