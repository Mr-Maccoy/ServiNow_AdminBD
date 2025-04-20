<?php
$conn = include_once __DIR__ . '/../../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['TRANID'])) {
    $tranid = $_POST['TRANID'];

    $query = "DELETE FROM TRANSACTIONS WHERE Transaction_ID = :tranid";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':tranid', $tranid);

    if (oci_execute($stmt)) {
        echo "Transacción eliminada correctamente.";
        header("Location: ../transacciones.php");
    } else {
        $e = oci_error($stmt);
        echo "Error al eliminar: " . $e['message'];
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>

