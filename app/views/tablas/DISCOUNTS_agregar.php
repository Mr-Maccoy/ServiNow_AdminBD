<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descuento = $_POST['descuento'];
    $sql = "INSERT INTO DISCOUNTS (DISCOUNT_APPLIED) VALUES (:descuento)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":descuento", $descuento);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <label>Descuento Aplicado:</label>
    <select name="descuento" required>
        <option value="Yes">Sí</option>
        <option value="No">No</option>
    </select>
    <button type="submit">Guardar</button>
</form>
