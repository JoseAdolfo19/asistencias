<?php
require_once 'db.php';
session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = sanitize_input($_POST['correo']);
    $password = $_POST['password'];

    // Prepare SQL to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, nombre, password_hash, rol_id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $usuario['password_hash'])) {
            // Start session
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol_id'] = $usuario['rol_id'];

            // Redirect based on role
            switch ($usuario['rol_id']) {
                case 1: // Admin
                    header("Location: dasboards/admin_dashboard.php");
                    break;
                case 2: // Supervisor
                    header("Location: dasboards/supervisor_dashboard.php");
                    break;
                case 3: // Practicante
                    header("Location: dasboards/practicante_dashboard.php");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        
body {
    background: url('../img/imagen.jpg') no-repeat center center fixed; 
    background-size: cover;
    height: 100vh;
    color: white;
}
.login-card {
    background: rgba(0, 0, 0, 0.6); /* Fondo oscuro y semi-transparente para el formulario */
    padding: 30px;
    border-radius: 10px;
}
.card-header {
    text-align: center;
    font-size: 24px;
    color: #fff;
}
.btn-primary {
    background-color: #4CAF50;
    border: none;
}
.form-group label {
    color: #fff;
}

    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card login-card">
                <div class="card-header">Iniciar Sesión</div>
                <div class="card-body">
                    <?php 
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                    ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Correo Electrónico:</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div><br>
                        <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                    </form>
                    <br>
                    <p><input type="checkbox" name="terminos" id="terminos">Acepta los <a href="#">terminos</a> y <a href="#">condiciones</a></p>
                    <div class="mt-3">

                        <p class="text-center">¿No tienes una cuenta? <a href="registra_sesion.php">Registrarse</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
