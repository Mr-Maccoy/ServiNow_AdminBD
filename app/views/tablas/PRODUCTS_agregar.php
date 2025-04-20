<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

// Obtener categorías para el select
$cat_sql = "SELECT CATEGORY_ID, CATEGORY_NAME FROM CATEGORIES";
$cat_stmt = oci_parse($conn, $cat_sql);
oci_execute($cat_stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $sql = "INSERT INTO PRODUCTS (PRODUCT_NAME, CATEGORY_ID) VALUES (:nombre, :categoria)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":categoria", $categoria);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <label>Nombre del Producto:</label>
    <input type="text" name="nombre" required><br>

    <label>Categoría:</label>
    <select name="categoria" required>
        <?php while ($cat = oci_fetch_array($cat_stmt, OCI_ASSOC)) {
            echo "<option value='{$cat['CATEGORY_ID']}'>{$cat['CATEGORY_NAME']}</option>";
        } ?>
    </select><br>

    <button type="submit">Guardar</button>
</form>
