<?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

// Obtener categorías
$cat_sql = "SELECT CATEGORY_ID, CATEGORY_NAME FROM CATEGORIES";
$cat_stmt = oci_parse($conn, $cat_sql);
oci_execute($cat_stmt);

// Obtener producto actual
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM PRODUCTS WHERE PRODUCT_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    $producto = oci_fetch_array($stmt, OCI_ASSOC);
    oci_free_statement($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $sql = "UPDATE PRODUCTS SET PRODUCT_NAME = :nombre, CATEGORY_ID = :categoria WHERE PRODUCT_ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":categoria", $categoria);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    header("Location: Miscelaneas.php");
    exit;
}
?>

<form method="post">
    <input type="hidden" name="id" value="<?= $producto['PRODUCT_ID'] ?>">
    <label>Nombre del Producto:</label>
    <input type="text" name="nombre" value="<?= $producto['PRODUCT_NAME'] ?>" required><br>

    <label>Categoría:</label>
    <select name="categoria" required>
        <?php
        oci_execute($cat_stmt); // Reset pointer
        while ($cat = oci_fetch_array($cat_stmt, OCI_ASSOC)) {
            $selected = $cat['CATEGORY_ID'] == $producto['CATEGORY_ID'] ? 'selected' : '';
            echo "<option value='{$cat['CATEGORY_ID']}' $selected>{$cat['CATEGORY_NAME']}</option>";
        }
        ?>
    </select><br>

    <button type="submit" name="update">Actualizar</button>
</form>
