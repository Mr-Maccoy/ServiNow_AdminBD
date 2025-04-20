<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM PAYMENT_METHODS WHERE PAYMENT_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    $pago = oci_fetch_array($stmt, OCI_ASSOC);
    oci_free_statement($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $metodo = $_POST['metodo'];
    $sql = "UPDATE PAYMENT_METHODS SET PAYMENT_METHOD = :metodo WHERE PAYMENT_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":metodo", $metodo);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $pago['PAYMENT_ID'] ?>">
    <label>Método de Pago:</label>
    <input type="text" name="metodo" value="<?= $pago['PAYMENT_METHOD'] ?>" required>
    <button type="submit" name="update">Actualizar</button>
</form>
