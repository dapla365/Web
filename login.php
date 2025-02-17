<?php include "./header.php"; ?>
<?php
// Capturar mensaje de error si existe
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Elimina el mensaje después de mostrarlo

$error = ($error_message) ? $messages["user"][$error_message] : null;
$accept = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once './keys/conexion_db.php';

    $email = strtolower(htmlspecialchars($_POST['email']));
    $password = htmlspecialchars($_POST['password']);

    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Validación en la base de datos
        $stmt = $pdo->prepare('SELECT id, email, password, rol FROM usuarios WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            $stored_password = $user['password'];
            $md5 = false;
            if (strlen($stored_password) == 32 && ctype_xdigit($stored_password)) {
                // La contraseña está en MD5
                if (md5($password) === $stored_password) {
                    // Autenticación exitosa con MD5, actualizar a bcrypt
                    $md5 = true;
                }
            }
            if (password_verify($password, $stored_password) || $md5) {
                // Inicio de sesión exitoso
                $rol = $user['rol'];
                $stmt = $pdo->prepare('SELECT id, rol FROM roles WHERE id = :rol');
                $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
                $stmt->execute();
    
                $rol = $stmt->fetch(PDO::FETCH_ASSOC);

                // Guardar en sesión y redirigir
                $_SESSION['email'] = $user['email'];
                $_SESSION['rol'] = $rol['rol'];

                if($md5){
                    // Cambiar contraseña de md5 a Bcript
                    $options = [
                        'cost' => 12,
                    ];

                    $new_hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);
    
                    $update_stmt = $pdo->prepare('UPDATE usuarios SET password = :new_password WHERE id = :id');
                    $update_stmt->bindParam(':new_password', $new_hashed_password, PDO::PARAM_STR);
                    $update_stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
                    $update_stmt->execute();

                    $accept = $messages["pass"]["md5_changed"];
                    header('Refresh:3; url=index.php');  // Redirige a la página principal despues del mensaje
                }else{
                    header('Location: index.php'); // Redirige a la página principal
                }
            } else{
                // Credenciales incorrectas
                $error = $messages["pass"]["incorrect"];
            }
        } else {
            // Credenciales incorrectas
            $error = $messages["pass"]["incorrect"];
        }
    }
}
?>

<body>
    <div class="card">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </div>
        <h1>Iniciar Sesión</h1>
        <p>Introduce tus credenciales para acceder.</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="email" name= "email" placeholder="Introduce tu email" required aria-label="Email">
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Login</button>
        </form>
        <div class="footer">
            <a href="#">Terminos</a>
            <a href="#">Privacidad</a>
            <a href="#">Ayuda</a>
            <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
            <?php if($accept != null) echo "<p class='accept'>$accept</p>"; ?>
            <?php if($error != null) echo "<p class='error'>$error</p>"; ?>
        </div>
    </div>
</body>
</html>