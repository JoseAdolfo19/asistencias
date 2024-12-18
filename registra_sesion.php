<?php
require_once 'db.php';
session_start();
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitizar y validar los datos de entrada
    $nombre = sanitize_input($_POST['nombre']);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $rol = intval($_POST['rol']);

    // Validar los datos
    if (empty($nombre) || empty($correo) || empty($password)) {
        $error = "Por favor, complete todos los campos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Formato de correo inválido.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Validar si el correo ya está registrado
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El correo ya está registrado.";
        } else {
            // Encriptar la contraseña
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Preparar y ejecutar la consulta de inserción
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password_hash, rol_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nombre, $correo, $password_hash, $rol);

            if ($stmt->execute()) {
                $success = "Registro exitoso. Puede iniciar sesión.";
            } else {
                $error = "Error al registrar: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('img/imagen.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }
        .login-card {
            background: rgba(0, 0, 0, 0.6); /* Fondo oscuro y semi-transparente para el formulario */
            padding: 30px;
            border-radius: 10px;
        }
        .card-header {
            text-align: center;
            font-size: 24px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Registro de Usuarios</div>
                <div class="card-body">
                    <?php
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                    if (!empty($success)) {
                        echo "<div class='alert alert-success'>$success</div>";
                    }
                    ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirmar Contraseña</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Rol</label>
                            <select name="rol" class="form-control" required>
                                <option value="">Seleccione un rol</option>
                                <option value="1">Admin</option>
                                <option value="2">Supervisor</option>
                                <option value="3">Practicante</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </form>
                    <div class="mt-3">
                        <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
