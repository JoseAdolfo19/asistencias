<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: ../login.php");
    exit();
}
require_once '../db.php';

if (isset($_POST['exportar'])) {
    $data = array();
    $query= "SELECT u.nombre, a.fecha, a.hora_entrada, a.hora_salida,
       CASE WHEN a.hora_entrada IS NOT NULL AND a.hora_salida IS NOT NULL THEN 'Completada' ELSE 'Incompleta' END AS estado
       FROM asistencia a
       JOIN usuarios u ON a.usuario_id = u.id
       ORDER BY a.fecha DESC;";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=asistencia.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<tr>
            <th>Practicante ID</th>
            <th>Fecha</th>
            <th>Hora de Entrada</th>
            <th>Hora de Salida</th>
            <th>Estado</th>
          </tr>";

    foreach ($data as $fila) {
        $hora_entrada = $fila['hora_entrada'] ?? null;
        $hora_salida = $fila['hora_salida'] ?? null;

        $estado = (!empty($hora_entrada) && !empty($hora_salida)) ? 'Completa' : 'Incompleta';

        echo "<tr>";
        echo "<td>{$fila['nombre']}</td>";
        echo "<td>{$fila['fecha']}</td>";
        echo "<td>{$hora_entrada}</td>";
        echo "<td>{$hora_salida}</td>";
        echo "<td>{$estado}</td>";
        echo "</tr>";
    }
    echo "</table>";

    exit;
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Asistencias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/practicante.css">  

</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                <a href="../dasboards/admin_dashboard.php"><i class="fas fa-tachometer-alt mr-2"></i>Panel de Administración</a>
                <a href="usuarios.php"><i class="fas fa-users mr-2"></i> Gestion de Usuarios</a>
                <a href="practicantes.php"><i class="fas fa-user-graduate mr-2"></i> Practicantes</a>
                <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
                <a href="#"> <i class="fas fa-calendar-check mr-2"></i>Asistencias</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Registro de Asistencias</h1>
                <div>
                    <a href="../dasboards/admin_dashboard.php" class="btn btn-outline-success">Volver al Inicio</a>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="exportar" class="btn btn-outline-primary">Exportar a XLSX</button>
                    </form>
                </div>           
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Practicante</th>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT a.*, u.nombre 
                                  FROM asistencia a 
                                  JOIN usuarios u ON a.usuario_id = u.id 
                                  ORDER BY a.fecha DESC";
                        $result = $conn->query($query);
                        while($asistencia = $result->fetch_assoc()) {
                            $estado = ($asistencia['hora_entrada'] && $asistencia['hora_salida']) 
                                      ? 'Completada' : 'Incompleta';
                            $estadoClass = $estado == 'Completada' ? 'success' : 'warning';
                            $hora_salida = $asistencia['hora_salida'] ? $asistencia['hora_salida'] : 'No registrada';

                            echo "<tr>
                                    <td>{$asistencia['nombre']}</td>
                                    <td>{$asistencia['fecha']}</td>
                                    <td>{$asistencia['hora_entrada']}</td>
                                    <td>{$hora_salida}</td>
                                    <td>
                                        <span class='badge badge-{$estadoClass}'>{$estado}</span>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
