<?php
$conn = include_once __DIR__ . '/../../../libraries/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $payment_id = $_POST['payment_id'];
    $discount_id = !empty($_POST['discount_id']) ? $_POST['discount_id'] : null;
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $query = "UPDATE TRANSACTIONS SET 
        Customer_ID = :customer_id,
        Product_ID = :product_id,
        Payment_ID = :payment_id,
        Discount_ID = :discount_id,
        Purchase_Amount = :amount,
        Purchase_Date = TO_DATE(:purchase_date, 'YYYY-MM-DD')
        WHERE Transaction_ID = :transaction_id";

    $statement = oci_parse($conn, $query);
    oci_bind_by_name($statement, ':customer_id', $customer_id);
    oci_bind_by_name($statement, ':product_id', $product_id);
    oci_bind_by_name($statement, ':payment_id', $payment_id);
    oci_bind_by_name($statement, ':discount_id', $discount_id);
    oci_bind_by_name($statement, ':amount', $amount);
    oci_bind_by_name($statement, ':purchase_date', $date);
    oci_bind_by_name($statement, ':transaction_id', $transaction_id);

    if (!oci_execute($statement)) {
        $e = oci_error($statement);
        die("Error al editar la transacción: " . $e['message']);
    }

    echo "Transacción actualizada exitosamente.";

    oci_free_statement($statement);
    oci_close($conn);
}
?>
