<?php include "./header.php"; ?>
<?php
// Verificar si el usuario ha iniciado sesión y es admin
if (!isset($_SESSION['email'])) {
    $_SESSION['error'] = "error_login";
    header("Location: login.php");
    exit;
}
$email = $_SESSION['email'];
$rol = $_SESSION['rol'];
if($rol != "ROLE_ADMIN"){
    $_SESSION['error'] = "error_admin";
    header("Location: no-autorizado.php");
    exit;
}

include "./keys/conexion_db.php";

// ROLES
try {
    $stmt = $pdo->prepare('SELECT * FROM roles');
    $stmt->execute();   
} catch (Exception $e) {

}
$roles = $stmt->fetchAll();
$rolesAsociativos = [];
foreach ($roles as $rol){
    $rolesAsociativos[$rol['id']] = $rol['rol'];
    if($rol['rol'] == 'ROLE_ADMIN'){
        $adminRol = $rol['id'];
    }
}

// USUARIOS
try {
    $stmt = $pdo->prepare('SELECT * FROM usuarios');
    $stmt->execute();   
} catch (Exception $e) {

}
$users = $stmt->fetchAll();
$adminUsers = 0;
$regularUsers = 0;
foreach ($users as $user){
    if($user['rol'] == $adminRol){
        $adminUsers+=1;
    }else{
        $regularUsers+=1;
    }
}
$totalUsers = count($users);

// Convierte los datos a formato JSON para usar en JavaScript
$chartData = json_encode([
    'adminUsers' => $adminUsers,
    'regularUsers' => $regularUsers,
    'totalUsers' => $totalUsers
]);

?>

<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="card_nav">
            <a href="index.php" class="button">Inicio</a>
            <a href="logout.php" class="button">Cerrar sesión</a>
        </div>

        <div class="card">
            <h2>User Statistics</h2>
            <div class="chart-container">
                <canvas id="userChart"></canvas>
            </div>
            <div class="user-count">
                Total Users: <?php echo $totalUsers; ?>
            </div>
        </div>

        <div class="card">
            <h2>User List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($rolesAsociativos[htmlspecialchars($user['rol'])]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Datos para el gráfico
        var chartData = <?php echo $chartData; ?>;

        // Crear el gráfico circular
        var ctx = document.getElementById('userChart').getContext('2d');
        var userChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Admin Users', 'Regular Users'],
                datasets: [{
                    data: [chartData.adminUsers, chartData.regularUsers],
                    backgroundColor: ['#9333ea', '#e9d5ff']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'User Roles Distribution'
                    }
                }
            }
        });
    </script>
</body>
</html>