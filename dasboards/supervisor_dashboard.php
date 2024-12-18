<?php
session_start();

// Verificar autenticación y rol de supervisor
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol_id'], [1, 2])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db.php';
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT * FROM usuarios WHERE id = '$usuario_id'";
$result = mysqli_query($conn, $query);
$supervisor = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Supervisor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-tachometer-alt mr-2"></i>Panel de Supervisor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../supervisor/usuarios.php">
                            <i class="fas fa-users mr-2"></i>Gestión de Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../supervisor/lista_asistencias.php">
                            <i class="fas fa-calendar-check mr-2"></i>Asistencias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Panel de Supervisor</h1>
                <h3 class="text-center">Bienvenido, <?php echo $supervisor['nombre']; ?></h3>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header"><a href="../supervisor/usuarios.php" class="btn btn-primary">Practicantes Activos</a>
                        </div>
                        <div class="card-body">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as total FROM practicantes");
                            $row = $result->fetch_assoc();
                            echo "<h5 class='card-title'>" . $row['total'] . " Practicantes</h5>";
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header"><a href="../supervisor/lista_asistencias.php" class ="btn btn-success">Asistencias Hoy</a>
                        </div>
                        <div class="card-body">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as total FROM asistencia WHERE fecha = CURDATE()");
                            $row = $result->fetch_assoc();
                            echo "<h5 class='card-title'>" . $row['total'] . " Registros</h5>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>