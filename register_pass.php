<?php include "./header.php"; ?>
<?php
// Verificar si el usuario ha validado el token
if (!isset($_SESSION['token']) || !isset($_SESSION['email'])) {
    $_SESSION['error'] = "error_register";
    header("Location: register.php");
    exit;
}

$accept = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos los valores del formulario
    $email = $_SESSION['email'];
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $hash = htmlspecialchars($_POST['hash']);  // Obtener tipo hash
    
    // Validaciones básicas
    if ($password !== $confirm_password) {
        $error = $messages["pass"]["no_match"];
        die();
    }

    if (!empty($email) && !empty($password) && !empty($confirm_password)) {
        require_once './keys/conexion_db.php';

        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = $messages["user"]["registered"];
        }else{
            // Registrar al usuario
            $options = [
                'cost' => 12,
            ];

            $hashed_password = ($hash == "md5") ? md5($password) :  password_hash($password, PASSWORD_BCRYPT, $options);

            $stmt = $pdo->prepare('INSERT INTO usuarios (email, password) VALUES (:email, :password)');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $accept = $messages["user"][$hash];

                $stmt = $pdo->prepare('DELETE FROM tokens WHERE email = :email');
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                unset($_SESSION['email']);
                unset($_SESSION['token']);
            } else {
                $error = $messages["user"]["register_error"];
            }
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
        <h1>Registra tu contraseña</h1>
        <p>Crea tu contraseña para iniciar sesión.</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type='email' placeholder='<?php
            $e = (isset($_SESSION['email'])) ? $_SESSION['email'] : $email;
            echo $e; 
            
            ?>' required aria-label='Email' disabled>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            <select name="hash" id="hash">
                <option value="bcript">BCRIPT</option>
                <option value="md5">MD5</option>
            </select>
            <button type="submit">Registrarse</button>
        </form>
        <div class="footer">
            <a href="#">Terminos</a>
            <a href="#">Privacidad</a>
            <a href="#">Ayuda</a>
            <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
            <?php if($accept != null) echo "<p class='accept'>$accept</p>"; ?>
            <?php if($error != null) echo "<p class='error'>$error</p>"; ?>
        </div>
    </div>
</body>
</html>

