<?php
$conn = include_once __DIR__ . '/../../../libraries/Database.php';

// Cargar opciones de claves foráneas
function getOptions($conn, $table, $id_field, $display_field) {
    $options = "";
    $query = "SELECT $id_field, $display_field FROM $table";
    $stmt = oci_parse($conn, $query);
    oci_execute($stmt);
    while ($row = oci_fetch_assoc($stmt)) {
        $id = $row[$id_field];
        $label = $row[$display_field];
        $options .= "<option value=\"$id\">$label</option>";
    }
    oci_free_statement($stmt);
    return $options;
}

$customers = getOptions($conn, 'CUSTOMERS', 'CUSTOMER_ID', 'CUSTOMER_ID');
$products = getOptions($conn, 'PRODUCTS', 'PRODUCT_ID', 'PRODUCT_NAME');
$payments = getOptions($conn, 'PAYMENT_METHODS', 'PAYMENT_ID', 'PAYMENT_METHOD');
$discounts = getOptions($conn, 'DISCOUNTS', 'DISCOUNT_ID', 'DISCOUNT_APPLIED');
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../head.php"); ?>
<body>
<header>
    <?php include("../menu.php"); ?>
</header>
<h1 class="mb-4">Agregar Transacción</h1>

<form action="insert.php" method="post" class="container" style="max-width: 600px;">
    <div class="mb-3">
        <label for="customer_id" class="form-label">Cliente:</label>
        <select id="customer_id" name="customer_id" class="form-select" required>
            <?= $customers ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="product_id" class="form-label">Producto:</label>
        <select id="product_id" name="product_id" class="form-select" required>
            <?= $products ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="payment_id" class="form-label">Método de Pago:</label>
        <select id="payment_id" name="payment_id" class="form-select" required>
            <?= $payments ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="discount_id" class="form-label">Descuento (opcional):</label>
        <select id="discount_id" name="discount_id" class="form-select">
            <option value="">Ninguno</option>
            <?= $discounts ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label">Monto de Compra:</label>
        <input type="number" step="0.01" id="amount" name="amount" class="form-control" required>
    </div>

    <div class="mb-4">
        <label for="date" class="form-label">Fecha de Compra:</label>
        <input type="date" id="date" name="date" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Agregar Transacción</button>
</form>


<footer>
    <?php include("../footer.php"); ?>
</footer>
</body>
</html>
