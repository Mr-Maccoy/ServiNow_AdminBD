<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo = $_POST['metodo'];
    $sql = "INSERT INTO PAYMENT_METHODS (PAYMENT_METHOD) VALUES (:metodo)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":metodo", $metodo);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <label>Método de Pago:</label>
    <input type="text" name="metodo" required>
    <button type="submit">Guardar</button>
</form>
