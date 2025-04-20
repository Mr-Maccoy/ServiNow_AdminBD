<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM CUSTOMERS WHERE CUSTOMER_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    $cliente = oci_fetch_array($stmt, OCI_ASSOC);
    oci_free_statement($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $ciudad = $_POST['ciudad'];

    $sql = "UPDATE CUSTOMERS SET AGE = :edad, GENDER = :genero, CITY = :ciudad WHERE CUSTOMER_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":edad", $edad);
    oci_bind_by_name($stmt, ":genero", $genero);
    oci_bind_by_name($stmt, ":ciudad", $ciudad);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: clientes.php");
    exit;
}
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $cliente['CUSTOMER_ID'] ?>">
    <label>Edad:</label>
    <input type="number" name="edad" value="<?= $cliente['AGE'] ?>" required><br>

    <label>Género:</label>
    <select name="genero" required>
        <option value="Male" <?= $cliente['GENDER'] == 'Male' ? 'selected' : '' ?>>Masculino</option>
        <option value="Female" <?= $cliente['GENDER'] == 'Female' ? 'selected' : '' ?>>Femenino</option>
        <option value="Other" <?= $cliente['GENDER'] == 'Other' ? 'selected' : '' ?>>Otro</option>
    </select><br>

    <label>Ciudad:</label>
    <input type="text" name="ciudad" value="<?= $cliente['CITY'] ?>" required><br>

    <button type="submit" name="update">Actualizar</button>
</form>
