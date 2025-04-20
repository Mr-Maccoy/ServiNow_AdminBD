<?php
$conn = include_once __DIR__ . '/../../../libraries/Database.php';

$transaccion = null;
$selected_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['TRANID'])) {
    $selected_id = $_POST['TRANID'];

    $stmt = oci_parse($conn, "SELECT * FROM TRANSACTIONS WHERE TRANSACTION_ID = :id");
    oci_bind_by_name($stmt, ":id", $selected_id);
    oci_execute($stmt);
    $transaccion = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
}

// Función para cargar opciones de clave foránea
function getOptions($conn, $table, $id_field, $display_field, $selected_value) {
    $options = "";
    $query = "SELECT $id_field, $display_field FROM $table";
    $stmt = oci_parse($conn, $query);
    oci_execute($stmt);
    while ($row = oci_fetch_assoc($stmt)) {
        $id = $row[$id_field];
        $label = $row[$display_field];
        $selected = ($id == $selected_value) ? "selected" : "";
        $options .= "<option value=\"$id\" $selected>$label</option>";
    }
    oci_free_statement($stmt);
    return $options;
}

$customers = getOptions($conn, 'CUSTOMERS', 'CUSTOMER_ID', 'CUSTOMER_ID', $transaccion['CUSTOMER_ID'] ?? null);
$products = getOptions($conn, 'PRODUCTS', 'PRODUCT_ID', 'PRODUCT_NAME', $transaccion['PRODUCT_ID'] ?? null);
$payments = getOptions($conn, 'PAYMENT_METHODS', 'PAYMENT_ID', 'PAYMENT_METHOD', $transaccion['PAYMENT_ID'] ?? null);
$discounts = getOptions($conn, 'DISCOUNTS', 'DISCOUNT_ID', 'DISCOUNT_APPLIED', $transaccion['DISCOUNT_ID'] ?? null);
?>

<h2 class="mb-4">Editar Transacción</h2>

<?php if ($transaccion): ?>

    <!DOCTYPE html>
    <html lang="en">
    <?php include("../head.php"); ?>
<body>
<header>
    <?php include("menu.php"); ?>
</header>
        
    
<form action="update.php" method="post" class="container" style="max-width: 600px;">
    <input type="hidden" name="transaction_id" value="<?= $transaccion['TRANSACTION_ID'] ?>">

    <div class="mb-3">
        <label for="customer_id" class="form-label">Cliente:</label>
        <select name="customer_id" class="form-select" required>
            <?= $customers ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="product_id" class="form-label">Producto:</label>
        <select name="product_id" class="form-select" required>
            <?= $products ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="payment_id" class="form-label">Método de Pago:</label>
        <select name="payment_id" class="form-select" required>
            <?= $payments ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="discount_id" class="form-label">Descuento:</label>
        <select name="discount_id" class="form-select">
            <option value="">Ninguno</option>
            <?= $discounts ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label">Monto:</label>
        <input type="number" step="0.01" name="amount" class="form-control" value="<?= $transaccion['PURCHASE_AMOUNT'] ?>" required>
    </div>

    <div class="mb-4">
        <label for="date" class="form-label">Fecha:</label>
        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d', strtotime($transaccion['PURCHASE_DATE'])) ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
</form>


<?php else: ?>
<p>No se encontró la transacción o no se ha seleccionado ninguna.</p>
<?php endif; ?>

<footer>
    <?php include("../footer.php"); ?>
</footer>
</body>
    </html>
<?php oci_close($conn); ?>
