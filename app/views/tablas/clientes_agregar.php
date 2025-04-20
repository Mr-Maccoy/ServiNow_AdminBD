<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $ciudad = $_POST['ciudad'];

    $sql = "INSERT INTO CUSTOMERS (AGE, GENDER, CITY) VALUES (:edad, :genero, :ciudad) RETURNING CUSTOMER_ID INTO :nuevo_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":edad", $edad);
    oci_bind_by_name($stmt, ":genero", $genero);
    oci_bind_by_name($stmt, ":ciudad", $ciudad);
    oci_bind_by_name($stmt, ":nuevo_id", $nuevo_id, 32);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);

    $mensaje = "Cliente agregado con ID: $nuevo_id";
}
?>

<form method="post">
    <label>Edad:</label>
    <input type="number" name="edad" required><br>

    <label>Género:</label>
    <select name="genero" required>
        <option value="Male">Masculino</option>
        <option value="Female">Femenino</option>
        <option value="Other">Otro</option>
    </select><br>

    <label>Ciudad:</label>
    <input type="text" name="ciudad" required><br>

    <button type="submit">Guardar</button>
</form>

<?php if ($mensaje): ?>
    <p><strong><?= $mensaje ?></strong></p>
    <a href="clientes.php" class="btn btn-primary">Volver a la lista</a>
<?php endif; ?>
