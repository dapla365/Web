<?php include "./header.php"; ?>
<?php
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email']) || isset($_SESSION['token'])) {
    $_SESSION['error'] = "error_login";
    header("Location: login.php");
    exit;
}

// Capturar mensaje de error si existe
$error_message = isset($_SESSION['error']) ? $messages["user"][$_SESSION['error']] : '';
unset($_SESSION['error']); // Elimina el mensaje de sesion después de obtenerlo

?>
<body>
    <div class="container">
        <?php 
        if ($error_message) echo "<p class='error'>$error_message</p>";
        ?>
    </div>
</body>
</html>