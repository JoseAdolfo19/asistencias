<?php 
session_start();  
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {     
    header("Location: ../login.php");     
    exit(); 
} 
require_once '../db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- [Incluir el mismo nav del panel anterior] -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users"></i> Practicantes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-file-alt"></i> Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Reportes</h1>
                <div>
                    <button class="btn btn-primary">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Asistencias por Mes</div>
                        <div class="card-body">
                            <?php
                            $query = "SELECT MONTH(fecha) as mes, COUNT(*) as total 
                                      FROM asistencia 
                                      GROUP BY MONTH(fecha)";
                            $result = $conn->query($query);
                            while($reporte = $result->fetch_assoc()) {
                                echo "<p>Mes {$reporte['mes']}: {$reporte['total']} asistencias</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Asistencias por Practicante</div>
                        <div class="card-body">
                            <?php
                            $query = "SELECT p.nombre, COUNT(a.id) as total 
                                      FROM practicantes p 
                                      LEFT JOIN asistencia a ON p.id = a.practicante_id 
                                      GROUP BY p.id";
                            $result = $conn->query($query);
                            while($reporte = $result->fetch_assoc()) {
                                echo "<p>{$reporte['nombre']}: {$reporte['total']} asistencias</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Asistencias Totales</div>
                        <div class="card-body">
                            <?php
                            $query = "SELECT COUNT(*) as total FROM asistencia";
                            $result = $conn->query($query);
                            $total_asistencias = $result->fetch_assoc()['total'];
                            echo "<p>Total de asistencias: {$total_asistencias}</p>";
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
