<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dpladia</title>
    <?php
    session_start();

    if ($_SERVER['PHP_SELF'] != "") {
        $url = explode("/", $_SERVER['PHP_SELF']);
        $url = $url[1]; // PARA LA PÁGINA WEB
        if ($url == 'login.php' || $url == 'register.php' || $url == 'register_pass.php') {
            echo '<link rel="stylesheet" href="css/formulario.css">';
        }
        if ($url == 'index.php' || $url == 'no-autorizado.php' || $url == 'token.php') {
            echo '<link rel="stylesheet" href="css/general.css">';
        }
        if ($url == 'admin.php') {
            echo '<link rel="stylesheet" href="css/admin.css">';
            echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
        }
    }

    // Cargar el archivo JSON
    $jsonFile = file_get_contents('msg/mensajes.json');

    // Decodificar el JSON a un array asociativo
    $messages = json_decode($jsonFile, true);

    // Verificar si la decodificación fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
    //  die("Error al decodificar el archivo JSON.");
    }
    ?>
</head>