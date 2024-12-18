<?php
session_start();
require_once '../db.php';

// Validate session and user role
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
    error_log("Intento de acceso no autorizado. User ID: " . ($_SESSION['usuario_id'] ?? 'Not set') . ", Role ID: " . ($_SESSION['rol_id'] ?? 'Not set'));
    header('Location: ../login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT * FROM usuarios WHERE id = '$usuario_id'";
$result = mysqli_query($conn, $query);
$practicante = mysqli_fetch_assoc($result);

$query = "SELECT * FROM asistencia WHERE usuario_id = '$usuario_id' AND fecha = DATE(NOW())";
$result = mysqli_query($conn, $query);
$asistencia = mysqli_fetch_assoc($result);

if (isset($_POST['marcar_entrada'])) {
    $query = "INSERT INTO asistencia (usuario_id, fecha, hora_entrada, hora_salida) VALUES ('$usuario_id', DATE(NOW()), TIME(NOW()), NULL)";
    mysqli_query($conn, $query);
    header('Location: practicante_dashboard.php');
    exit;
}
if (isset($_POST['marcar_salida'])) {
    $query = "UPDATE asistencia SET hora_salida = TIME(NOW()) WHERE usuario_id = '$usuario_id' AND fecha = DATE(NOW())";
    mysqli_query($conn, $query);
    header('Location: practicante_dashboard.php');
    exit;

    if ($asistencia_registro && $asistencia_registro['hora_salida'] != NULL) {
        echo "Ya has marcado la salida para hoy";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel del Practicante</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/practicante.css">  
</head>
<body>
    <header>
        <div class="sidebar">
            <h4 class="text-center">Menú</h4>
            <a href="#"><i class="fas fa-home"></i> Inicio</a>
            <a href="../practicantes/perfil.php"><i class="fas fa-user"></i> Perfil</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </header>
    <main>
    <section class="container">
        <h1 class="text-center">Bienvenido, <?php echo $practicante['nombre']; ?></h1>
        <?php if (!$asistencia) { ?>
            <form action="" method="post">
                <button type="submit" name="marcar_entrada" class="btn btn-primary btn-block">Marcar Entrada</button>
            </form>
        <?php } else { ?>
            <form action="" method="post">
                <button type="submit" name="marcar_salida" class="btn btn-primary btn-block">Marcar Salida</button>
            </form>
        <?php } ?>
        <h2 class="text-center">Asistencia</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora Entrada</th>
                    <th>Hora Salida</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM asistencia WHERE usuario_id = '$usuario_id'";
                $result = mysqli_query($conn, $query);
                while ($asistencia_registro = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$asistencia_registro['fecha']}</td>
                            <td>{$asistencia_registro['hora_entrada']}</td>
                            <td>{$asistencia_registro['hora_salida']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>