<?php

$conn = include_once __DIR__ . '/../../../libraries/Database.php';
// Código para guardar la transacción
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $payment_id = $_POST['payment_id'];
    $discount_id = !empty($_POST['discount_id']) ? $_POST['discount_id'] : null;
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $query = "INSERT INTO TRANSACTIONS 
    (Customer_ID, Product_ID, Payment_ID, Discount_ID, Purchase_Amount, Purchase_Date)
    VALUES (:customer_id, :product_id, :payment_id, :discount_id, :amount, TO_DATE(:purchase_date, 'YYYY-MM-DD'))";

$statement = oci_parse($conn, $query);
oci_bind_by_name($statement, ':customer_id', $customer_id);
oci_bind_by_name($statement, ':product_id', $product_id);
oci_bind_by_name($statement, ':payment_id', $payment_id);
oci_bind_by_name($statement, ':discount_id', $discount_id);
oci_bind_by_name($statement, ':amount', $amount);
oci_bind_by_name($statement, ':purchase_date', $date);

    if (!oci_execute($statement)) {
        $e = oci_error($statement);
        die("Error al agregar la transacción: " . $e['message']);
    }

    echo "Transacción agregada exitosamente.";

    oci_free_statement($statement);
    oci_close($conn);
}
?>