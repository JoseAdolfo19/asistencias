<?php
include '../db.php'; // Incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $rol_id = $_POST['rol_id'];

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($correo) && !empty($password) && !empty($rol_id)) {
        // Encriptar la contraseña
        $password_encriptada = password_hash($password, PASSWORD_BCRYPT);

        // Insertar datos en la base de datos
        $sql = "INSERT INTO usuarios (nombre, correo, password_hash, rol_id) 
                VALUES ('$nombre', '$correo', '$password_encriptada', '$rol_id')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Usuario guardado correctamente');
                    window.location.href = 'usuarios.php';
                  </script>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "<script>alert('Todos los campos son obligatorios');</script>";
    }
}
?>
