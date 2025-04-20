<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM CATEGORIES WHERE CATEGORY_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    $categoria = oci_fetch_array($stmt, OCI_ASSOC);
    oci_free_statement($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $nombre = $_POST['nombre'];
    $sql = "UPDATE CATEGORIES SET CATEGORY_NAME = :nombre WHERE CATEGORY_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $categoria['CATEGORY_ID'] ?>">
    <label>Nombre Categoría:</label>
    <input type="text" name="nombre" value="<?= $categoria['CATEGORY_NAME'] ?>" required>
    <button type="submit" name="update">Actualizar</button>
</form>
