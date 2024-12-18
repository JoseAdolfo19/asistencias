<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM usuarios WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}

// Redirigir de nuevo a la lista
header("Location: usuarios.php");
exit();
?>
