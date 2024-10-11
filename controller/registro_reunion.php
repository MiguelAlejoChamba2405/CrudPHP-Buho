<?php
include "model/conx.php";

if (isset($_POST['btn_submit'])) {
    // Obtén los valores del formulario
    $nombre = $_POST['nombre'];
    $etiquetas = $_POST['etiquetas']; // Array de etiquetas seleccionadas
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $integrantes = $_POST['integrantes']; // Array de integrantes seleccionados

    // Inserta los datos en la tabla 'reuniones'
    $stmt = $conexion->prepare("INSERT INTO reuniones (nombre, fecha_inicio, fecha_fin) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $nombre, $fecha_inicio, $fecha_fin);
    if ($stmt->execute()) {
        $reunion_id = $stmt->insert_id; // Obtener el ID de la reunión recién insertada
        $stmt->close();

        // Inserta las etiquetas asociadas en la tabla intermedia 'reunion_etiquetas'
        $stmt_etiquetas = $conexion->prepare("INSERT INTO reunion_etiquetas (reunion_id, etiqueta_id) VALUES (?, ?)");
        foreach ($etiquetas as $etiqueta_id) {
            $stmt_etiquetas->bind_param('ii', $reunion_id, $etiqueta_id);
            $stmt_etiquetas->execute();
        }
        $stmt_etiquetas->close();

        // Crear la carpeta para guardar la reunión
        $directorio = 'reunion_usuario';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true); // Crear la carpeta si no existe
        }

        // Guardar los contactos en la base de datos
        $stmt_usuario = $conexion->prepare("INSERT INTO reunion_usuarios (reunion_id, usuario_id) VALUES (?, ?)");
        foreach ($integrantes as $integrante_id) {
            $stmt_usuario->bind_param('ii', $reunion_id, $integrante_id);
            $stmt_usuario->execute();
        }
        $stmt_usuario->close();

        // Obtener los números de teléfono de los integrantes
        $telefonos = [];
        $stmt_telefonos = $conexion->prepare("SELECT numero FROM usuarios WHERE id = ?");
        foreach ($integrantes as $integrante_id) {
            $stmt_telefonos->bind_param('i', $integrante_id);
            $stmt_telefonos->execute();
            $stmt_telefonos->bind_result($numero);
            while ($stmt_telefonos->fetch()) {
                $telefonos[] = $numero;
            }
        }
        $stmt_telefonos->close();

        // Enviar el mensaje de la reunión usando la API para cada número de teléfono
        $message = "Saludos, a las " . $fecha_inicio . " habrá una reunión hasta las " . $fecha_fin . ".";
        foreach ($telefonos as $telefono) {
            enviarMensaje($telefono, $message);
        }

        // Confirmar creación de la reunión
        echo "Reunión creada exitosamente y mensajes enviados.";
    } else {
        echo "Error al crear la reunión: " . $conexion->error;
    }
}

// Función para enviar el mensaje utilizando la API
function enviarMensaje($number, $message)
{
    // URL de la API
    $url = "https://1476424.senati.buho.xyz/api/message/send-text"; // Cambia la URL de la API si es necesario

    // Datos para el envío en formato JSON
    $data = array(
        "number" => "51". $number,
        "message" => $message
    );

    // Cabeceras
    $headers = array(
        'Authorization: Bearer 4HphHxry6gsmSrORIzJiaSiYtO1Hng', // Reemplaza TOKEN por el valor correcto
        'Content-Type: application/json'
    );

    // Inicia cURL
    $ch = curl_init($url);

    // Configura cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Ejecuta la solicitud
    $response = curl_exec($ch);

    // Verifica si hubo algún error
    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
    }

    // Cierra cURL
    curl_close($ch);
}
?>