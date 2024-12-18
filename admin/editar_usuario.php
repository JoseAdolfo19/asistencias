<?php
include '../db.php';

// Obtener datos del usuario
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
}

// Guardar cambios en la base de datos
if (isset($_POST['actualizar_usuario'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol_id = $_POST['rol_id'];

    $sql = "UPDATE usuarios SET nombre = ?, correo = ?, rol_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $nombre, $correo, $rol_id, $id);
    if ($stmt->execute() === TRUE) {
        echo "<script>
                alert('Usuario actualizado correctamente');
                window.location.href = 'usuarios.php';
            </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<link rel="stylesheet" href="../css/ediatr.css">
<div class="container my-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Editar Usuario
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                <div class="form-group">
                    <label for="nombre"><strong>Nombre:</strong></label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $usuario['nombre']; ?>">
                </div>
                <div class="form-group">
                    <label for="correo"><strong>Correo:</strong></label>
                    <input type="email" class="form-control" name="correo" id="correo" value="<?php echo $usuario['correo']; ?>">
                </div>
                <div class="form-group">
                    <label for="rol_id"><strong>Rol:</strong></label>
                    <select class="form-control" name="rol_id" id="rol_id">
                        <?php
                        $roles_query = "SELECT * FROM roles";
                        $roles_result = $conn->query($roles_query);
                        while ($row = $roles_result->fetch_assoc()) {
                            $selected = ($row['id'] == $usuario['rol_id']) ? 'selected' : '';
                            echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="actualizar_usuario">Actualizar</button>
            </form>
        </div>
    </div>
</div>
