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
                <label for="nombre" class="form-label">Nombre de la persona</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido de la persona</label>
                <input type="text" class="form-control" id="apellido" name="apellido">
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI de la persona</label>
                <input type="text" class="form-control" id="dni" name="dni">
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fecha" name="fecha">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electronico</label>
                <input type="email" class="form-control" id="correo" name="correo">
            </div>

            <button type="submit" class="btn btn-primary" name="btnregistrar" value="ok">Registrar</button>
        </form>
        <div class="col-8 p-4">
            <table class="table">
                <thead class="bg-info">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">NOMBRES</th>
                        <th scope="col">APELLIDOS</th>
                        <th scope="col">DNI</th>
                        <th scope="col">FECHA DE NAC</th>
                        <th scope="col">CORREO</th>
                        <th scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        include "model/conx.php";
                        $sql = $conexion->query("SELECT * FROM usuario");
                        while($datos = $sql->fetch_object()) { ?>
                    <tr>
                        <td><?= $datos->id_persona ?></td>
                        <td><?= $datos->nombre ?></td>
                        <td><?= $datos->apellido ?></td>
                        <td><?= $datos->dni ?></td>
                        <td><?= $datos->fecha_nac ?></td>
                        <td><?= $datos->correo ?></td>
                        <td>
                            <!-- Botón para eliminar el usuario -->
                            <form method="POST" action="controller/eliminar_usuario.php" style="display:inline;">
                                <input type="hidden" name="id_persona" value="<?= $datos->id_persona ?>">
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar a esta persona?');">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                            <!-- Botón que activa el modal para modificar la persona -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modificarPersonasModal" data-id="<?= $datos->id_persona ?>"
                                data-nombre="<?= $datos->nombre ?>" data-apellido="<?= $datos->apellido ?>"
                                data-dni="<?= $datos->dni ?>" data-fecha="<?= $datos->fecha_nac ?>"
                                data-correo="<?= $datos->correo ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
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
                            <label for="nombre" class="form-label">Nombre de la persona</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido de la persona</label>
                            <input type="text" class="form-control" id="apellido" name="apellido">
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI de la persona</label>
                            <input type="text" class="form-control" id="dni" name="dni">
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fecha" name="fecha">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electronico</label>
                            <input type="email" class="form-control" id="correo" name="correo">
                        </div>
                        <input type="hidden" id="id_persona" name="id_persona">
                        <button type="submit" class="btn btn-primary" name="btnModificar" value="ok">Modificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para mostrar mensaje de éxito -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Éxito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Usuario modificado correctamente.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Boostrap -->
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
            var apellido = button.getAttribute('data-apellido');
            var dni = button.getAttribute('data-dni');
            var fecha = button.getAttribute('data-fecha');
            var correo = button.getAttribute('data-correo');

            // Actualizar los valores del formulario dentro del modal
            var modalForm = modificarModal.querySelector('#modificarPersonasForm');
            modalForm.querySelector('#nombre').value = nombre;
            modalForm.querySelector('#apellido').value = apellido;
            modalForm.querySelector('#dni').value = dni;
            modalForm.querySelector('#fecha').value = fecha;
            modalForm.querySelector('#correo').value = correo;

            // Añadir el campo oculto con el ID del usuario
            var inputID = modalForm.querySelector('#id_persona');
            if (!inputID) {
                inputID = document.createElement('input');
                inputID.type = 'hidden';
                inputID.name = 'id_persona';
                inputID.id = 'id_persona';
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

        var formData = new FormData(this);

        fetch('controller/registro_personal.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                var modal = bootstrap.Modal.getInstance(document.getElementById('modificarPersonasModal'));
                modal.hide();
                window.location.href = window.location.pathname +
                '?actualizado=true'; // Redirigir con parámetro
            })
            .catch(error => console.error('Error:', error));
    });
    </script>
</body>

</html>