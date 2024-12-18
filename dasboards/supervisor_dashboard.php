<?php
session_start();

// Verificar autenticación y rol de supervisor
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol_id'], [1, 2])) {
    header("Location: ../login.php");
    exit();
}

require_once '../db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Supervisor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/supervisor.css">
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
                            <i class="fas fa-home"></i>Panel de Supervisor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Panel de Supervisor</h1>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Practicantes Activos</div>
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
                        <div class="card-header">Asistencias Hoy</div>
                        <div class="card-body">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as total FROM asistencia WHERE fecha = CURDATE()");
                            $row = $result->fetch_assoc();
                            echo "<h5 class='card-title'>" . $row['total'] . " Registros</h5>";
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header">Últimas Asistencias</div>
                        <div class="card-body">
                            <?php
                            $result = $conn->query("SELECT p.nombre, a.hora_entrada FROM asistencia a 
                                                    JOIN practicantes p ON a.practicante_id = p.id 
                                                    WHERE a.fecha = CURDATE() 
                                                    ORDER BY a.hora_entrada DESC LIMIT 5");
                            echo "<ul class='list-unstyled'>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<li>" . $row['nombre'] . " - " . $row['hora_entrada'] . "</li>";
                            }
                            echo "</ul>";
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