<?php include "./header.php"; ?>

<?php
$error_message = null;
if (isset($_GET['email']) && isset($_GET['token'])) {
    require_once './keys/conexion_db.php';

    $email = htmlspecialchars($_GET['email']);
    $token = htmlspecialchars($_GET['token']);

    //Expresion regular para comprobar que el token esta entre los valores 10000 y 99999
    if (!empty($email) && !empty($token) && preg_match('/^[1-9]\d{4}$/', $token)) {      
        $stmt = $pdo->prepare("SELECT * FROM tokens WHERE email = :email AND token = :token AND creado_en >= (NOW() - INTERVAL 10 MINUTE)");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $token_bd = $resultado['token'];
        $creado_en = $resultado['creado_en']; // Formato: 'Y-m-d H:i:s'

        // Convertir la fecha de la base de datos a un objeto DateTime
        $fecha_creacion = new DateTime($creado_en);
        $ahora = new DateTime();

        // Calcular la diferencia en minutos
        $diferencia = $fecha_creacion->diff($ahora)->i; // Extrae la diferencia en minutos
        
        if ($diferencia <= 5) { // Compara que no haya pasado mas de 5min
            $_SESSION['token'] = true;
            $_SESSION['email'] = $email;
            header("Location: register_pass.php?email=$email");
        } else {
            $error_message = $messages['token']['invalid'];
        }

    } else {
        $error_message = $messages['token']['wrong_data'];
    }
}else {
    $error_message = $messages['token']['wrong_data'];
}
?>


<body>
    <div class="container">
        <?php 
        if ($error_message != null) echo "<p class='error'>$error_message</p>";
        ?>
    </div>
</body>
</html>

