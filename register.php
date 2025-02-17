<?php include "./header.php"; ?>
<?php
$accept = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: keys/mail.php?register=true&email=$email");
    } else {
        $error = "Email inválido.";
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
        <h1>Registrate en dpladia</h1>
        <p>Sincroniza tu email mediante una confirmación.</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="email" name= "email" placeholder="Introduce tu email" required aria-label="Email">
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