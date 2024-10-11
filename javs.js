// Función para enviar un mensaje de texto
async function sendMessage(number, message) {
    const token = "4HphHxry6gsmSrORIzJiaSiYtO1Hng"; // Aquí va tu token

    const url = "https://1476424.senati.buho.xyz/dashboard"; // Reemplaza con tu URL real
    const headers = {
        "Content-Type": "application/json"
    };
    
    const body = {
        "number": number,
        "message": message
    };

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: headers,
            body: JSON.stringify(body)
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Mensaje enviado con éxito:", data);
        } else {
            console.error("Error al enviar el mensaje:", response.statusText);
        }
    } catch (error) {
        console.error("Error de conexión:", error);
    }
}

// Número de Perú y mensaje a enviar
const numeroPeru = "51987654321"; // Reemplaza con el número de Perú (formato internacional)
const mensaje = "Hola, este es un mensaje de prueba";

// Llamar a la función para enviar el mensaje
sendMessage(numeroPeru, mensaje);