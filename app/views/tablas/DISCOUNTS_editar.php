<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM DISCOUNTS WHERE DISCOUNT_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    $descuento = oci_fetch_array($stmt, OCI_ASSOC);
    oci_free_statement($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nuevo = $_POST['descuento'];
    $sql = "UPDATE DISCOUNTS SET DISCOUNT_APPLIED = :descuento WHERE DISCOUNT_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":descuento", $nuevo);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $descuento['DISCOUNT_ID'] ?>">
    <label>Descuento Aplicado:</label>
    <select name="descuento" required>
        <option value="Yes" <?= $descuento['DISCOUNT_APPLIED'] == 'Yes' ? 'selected' : '' ?>>Sí</option>
        <option value="No" <?= $descuento['DISCOUNT_APPLIED'] == 'No' ? 'selected' : '' ?>>No</option>
    </select>
    <button type="submit" name="update">Actualizar</button>
</form>
