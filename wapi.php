<?php
// Verificar si se ha recibido una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $number = $_POST['number'];
    $message = $_POST['message'];

    // URL de la API
    $url = "https://1476424.senati.buho.xyz/api/message/send-text"; // Cambia la URL de la API aquí

    // Datos para el envío en formato JSON
    $data = array(
        "number" => $number,
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
        echo json_encode(array("error" => curl_error($ch))); // Devolver el error como JSON
    } else {
        // Devuelve la respuesta de la API en formato JSON
        echo json_encode(array("response" => $response));
    }

    // Cierra cURL
    curl_close($ch);
    exit(); // Terminar el script aquí para que solo se ejecute el código PHP en solicitudes POST
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Mensaje</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <!-- Asegúrate de tener jQuery -->
</head>
<body>
    <h1>Enviar Mensaje</h1>
    <form id="messageForm">
        <label for="number">Número de Teléfono:</label><br>
        <input type="text" id="number" name="number" required><br><br>

        <label for="message">Mensaje:</label><br>
        <textarea id="message" name="message" required></textarea><br><br>

        <input type="submit" value="Enviar Mensaje">
    </form>

    <!-- Aquí se mostrará la respuesta de la API -->
    <div id="response"></div>

    <script>
        $(document).ready(function(){
            $('#messageForm').on('submit', function(e){
                e.preventDefault(); // Prevenir que el formulario se envíe de forma normal

                // Obtener los datos del formulario
                var number = $('#number').val();
                var message = $('#message').val();

                // Realizar la solicitud AJAX
                $.ajax({
                    url: '', // Aquí se usa el mismo archivo para manejar el envío
                    type: 'POST',
                    data: {
                        number: number,
                        message: message
                    },
                    dataType: 'json',
                    success: function(response){
                        if(response.error){
                            // Mostrar el error en el div 'response'
                            $('#response').html('<p>Error: ' + response.error + '</p>');
                        } else {
                            // Mostrar la respuesta de la API en el div 'response'
                            $('#response').html('<p>Respuesta de la API: ' + response.response + '</p>');
                        }
                    },
                    error: function(){
                        $('#response').html('<p>Ocurrió un error al enviar el mensaje.</p>');
                    }
                });
            });
        });
    </script>
</body>
</html>