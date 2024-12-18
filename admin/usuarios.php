<?php 
session_start();  
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {     
    header("Location: ../login.php");     
    exit(); 
} 
require_once '../db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT u.nombre, u.correo, u.password_hash, r.nombre AS rol_nombre 
                        FROM usuarios u 
                        JOIN roles r ON u.rol_id = r.id 
                        WHERE u.id = ?");
    $result = $query->query($stmt);
    $usuario = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-tachometer-alt mr-2"></i>Panel de Administración
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/usuarios.php">
                            <i class="fas fa-users mr-2"></i>Gestión de Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/practicantes.php">
                            <i class="fas fa-user-graduate mr-2"></i>Practicantes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/asistencias.php">
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
        <main class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestión de Usuarios</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#nuevoUsuarioModal">
                    <i class="fas fa-plus"></i> Nuevo Usuario
                </button>
                <a href="../dasboards/admin_dashboard.php" class="btn btn-outline-success">Volver al Panel</a>

            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $conn->prepare("SELECT u.*, r.nombre as rol_nombre 
                                                FROM usuarios u 
                                                JOIN roles r ON u.rol_id = r.id");
                        $stmt->execute();
                        
                        // Obtener los resultados
                        $result = $stmt->get_result();
                        
                        // Verificar si se obtuvieron resultados
                        if ($result->num_rows > 0) {
                            while ($usuario = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$usuario['id']}</td>
                                        <td>{$usuario['nombre']}</td>
                                        <td>{$usuario['correo']}</td>
                                        <td>{$usuario['rol_id']}</td>
                                        <td>
                                            <a href='editar_usuario.php?id={$usuario['id']}' class='btn btn-sm btn-warning'>
                                                <i class='fas fa-edit'></i> Editar
                                            </a>
                                            <a href='eliminar_usuario.php?id={$usuario['id']}' 
                                               class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\")'>
                                                <i class='fas fa-trash'></i> Eliminar
                                            </a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "No se encontraron usuarios.";
                        }                       
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Usuario</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="guardar_usuario.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="rol_id" class="form-control" required>
                            <?php
                            include 'db.php'; // Conexión a la base de datos
                            $roles = $conn->query("SELECT * FROM roles");
                            while ($rol = $roles->fetch_assoc()) {
                                echo "<option value='{$rol['id']}'>{$rol['nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>