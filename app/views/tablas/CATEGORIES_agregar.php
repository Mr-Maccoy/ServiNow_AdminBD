<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $sql = "INSERT INTO CATEGORIES (CATEGORY_NAME) VALUES (:nombre)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <label>Nombre Categoría:</label>
    <input type="text" name="nombre" required>
    <button type="submit">Guardar</button>
</form>
