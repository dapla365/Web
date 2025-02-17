<?php include "./header.php"; ?>
<?php
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email']) || isset($_SESSION['token'])) {
    $_SESSION['error'] = "error_login";
    header("Location: login.php");
    exit;
}
$email = htmlspecialchars($_SESSION['email']);
$rol = htmlspecialchars($_SESSION['rol']);

?>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo $email; ?> 👋</h2>
        <p>Rol: <?php echo $rol; ?></p>
        <a href="logout.php" class="logout">Cerrar sesión</a>
        <?php 
        if($rol == "ROLE_ADMIN") echo '<a href="admin.php" class="logout">Admin</a>';
        ?>
    </div>
</body>
</html>


