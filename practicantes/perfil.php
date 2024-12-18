<?php
session_start();
// Verificar autenticación
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3 ) {
    error_log("Intento de acceso no autorizado. User ID: " . ($_SESSION['usuario_id'] ?? 'Not set') . ", Role ID: " . ($_SESSION['rol_id'] ?? 'Not set'));
    header('Location: ../login.php');
    exit;
}
require_once '../db.php';
$usuario_id = $_SESSION['usuario_id'];

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT u.nombre, u.correo, u.password_hash, r.nombre AS rol_nombre 
                        FROM usuarios u 
                        JOIN roles r ON u.rol_id = r.id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $password_hash = isset($usuario['password_hash']) ? $usuario['password_hash'] : '';
    }

    $update_query = "UPDATE usuarios SET nombre = ?, correo = ?, password_hash = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $nombre, $correo, $password_hash, $usuario_id);

    if ($update_stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Perfil actualizado con éxito.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar el perfil.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/practicante.css">  

</head>
<body>
<div class="container mt-5">
<header>
        <div class="sidebar">
            <h4 class="text-center">Menú</h4>
            <a href="../dasboards/practicante_dashboard.php"><i class="fas fa-home"></i> Inicio</a>
            <a href="#"><i class="fas fa-user"></i> Perfil</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </header>
    <h2>Mi Perfil</h2>
    <div class="card">
        <div class="card-header">
            Información del Usuario
        </div>
        <div class="card-body">
            <?php if ($usuario): ?>
                <p><strong><i class="fas fa-user"></i> Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
                <p><strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hammer" viewBox="0 0 16 16">
                            <path d="M9.972 2.508a.5.5 0 0 0-.16-.556l-.178-.129a5 5 0 0 0-2.076-.783C6.215.862 4.504 1.229 2.84 3.133H1.786a.5.5 0 0 0-.354.147L.146 4.567a.5.5 0 0 0 0 .706l2.571 2.579a.5.5 0 0 0 .708 0l1.286-1.29a.5.5 0 0 0 .146-.353V5.57l8.387 8.873A.5.5 0 0 0 14 14.5l1.5-1.5a.5.5 0 0 0 .017-.689l-9.129-8.63c.747-.456 1.772-.839 3.112-.839a.5.5 0 0 0 .472-.334"/>
                        </svg>
                            Rol:
                    </strong>
                    <?php echo htmlspecialchars($usuario['rol_nombre']); ?>
                </p>
                <p><strong><i class="fas fa-envelope"></i> Correo Electrónico:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></p>
            <?php else: ?>
                <p>No se encontraron datos del usuario.</p>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <div>   
        <?php if (isset($mensaje)): ?>
            <?php echo $mensaje; ?>
        <?php endif; ?>
    <div class="card-header">
            <h4>Editar Usuario</h4>
        </div>
        <div class="card-body">
     
            <form action="perfil.php" method ="POST">
                <p><strong>Nombre:</strong>
                    <input type="text" name="nombre" id="nombre" value="<?php echo $usuario['nombre']; ?>">
                </p>
                <p><strong>Correo:</strong>
                    <input type="email" name="correo" id="correo" value="<?php echo $usuario['correo']; ?>">
                </p>
                <p><strong>Contraseña:</strong>
                    <input type="password" name="password" id="password">
                </p>
                <button type="submit" class="btn btn-secondary" name="actualizar_perfil">Actualizar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>