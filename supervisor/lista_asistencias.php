<?php
require_once '../db.php';

// Validación de columnas permitidas
$allowed_columns = ['fecha', 'practicante_id'];
$order_by = isset($_GET['order_by']) && in_array($_GET['order_by'], $allowed_columns) ? $_GET['order_by'] : 'fecha';

// Consulta principal con el ORDER BY seguro
$query = "
    SELECT a.id, p.nombre, p.correo, a.fecha, a.hora_entrada, a.hora_salida
    FROM asistencia a
    JOIN practicantes p ON a.practicante_id = p.id
    ORDER BY $order_by ASC
";
$result = $conn->query($query);

?>
<h2 class="text-center">Reportes</h2>

<!-- Selector de Ordenamiento -->
<div class="row mb-3 w-25 mx-auto text-center p-4">
    <div class="col-md-6">
        <form method="GET" class="d-flex align-items-center">
            <label for="order-by" class="me-2">Ordenar por:</label>
            <select name="order_by" id="order-by" class="form-select w-auto" onchange="this.form.submit()">
                <?php 
                $order_options = [
                    'fecha' => 'Fecha',
                    'practicante_id' => 'ID del Practicante'
                ];
                foreach ($order_options as $key => $label): ?>
                    <option value="<?php echo htmlspecialchars($key); ?>" 
                        <?php echo $order_by === $key ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<!-- Tabla de Asistencias -->
<table cellspacing="0" cellpadding="5">
    <tr>
        <th>N°</th>
        <th>Nombre del Practicante</th>
        <th>Correo</th>
        <th>Fecha</th>
        <th>Hora de Entrada</th>
        <th>Hora de Salida</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']); ?></td>
                <td><?= htmlspecialchars($row['nombre']); ?></td>
                <td><?= htmlspecialchars($row['correo']); ?></td>
                <td><?= htmlspecialchars($row['fecha']); ?></td>
                <td><?= htmlspecialchars($row['hora_entrada']); ?></td>
                <td><?= $row['hora_salida'] ? htmlspecialchars($row['hora_salida']) : 'No registrada'; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No hay registros disponibles.</td>
        </tr>
    <?php endif; ?>
</table>
