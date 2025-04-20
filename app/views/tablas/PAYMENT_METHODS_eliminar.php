<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $sql = "DELETE FROM PAYMENT_METHODS WHERE PAYMENT_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>
