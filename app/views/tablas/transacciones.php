<!DOCTYPE html>
<html lang="en">
<?php include("../head.php"); ?>
<body>
<header>
    <?php include("menu.php"); ?>
</header>

<div class="jumbotron jumbotron-fluid text-center">
    <div class="container">
        <h1 class="display-3">Transacciones</h1>
        <form method="get" action="" class="mb-3">
    <label for="limite" class="form-label">Cantidad de transacciones a mostrar:</label>
    <input type="number" id="limite" class="form-control" name="limite" value="<?= isset($_GET['limite']) ? $_GET['limite'] : 10 ?>" min="1">
    <button type="submit" class="btn btn-primary mt-3">Aplicar</button>

    <div class="mb-3">
    <label for="customer_filter" class="form-label">Filtrar por ID de Cliente (opcional):</label>
    <input type="number" id="customer_filter" class="form-control" name="customer_id" value="<?= isset($_GET['customer_id']) ? $_GET['customer_id'] : '' ?>">
</div>


</form>
<br>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Cliente</th>
                    <th>Ciudad</th>
                    <th>Producto</th>
                    <th>Categoria</th>
                    <th>Fecha de Compra</th>
                    <th>Monto</th>
                    <th>Metodo de Pago</th>
                    <th>Descuento</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                    
                </tr>
            </thead>
            <tbody>

            <?php
$conn = include_once __DIR__ . '/../../libraries/Database.php';

$limite = isset($_GET['limite']) && is_numeric($_GET['limite']) ? intval($_GET['limite']) : 10;


$customer_id = isset($_GET['customer_id']) && is_numeric($_GET['customer_id']) ? intval($_GET['customer_id']) : null;

if ($customer_id) {
    $sql = "
        SELECT 
            t.Transaction_ID AS TRANID,
            c.Customer_ID AS CUSTID,
            c.City AS CITY,
            p.Product_Name AS PNAME,
            cat.Category_Name AS CATNAME,
            t.Purchase_Date AS PDATE,
            t.Purchase_Amount AS PAMOUNT,
            pm.Payment_Method AS METHOD,
            d.Discount_Applied AS DISC
        FROM TRANSACTIONS t
        JOIN CUSTOMERS c ON t.Customer_ID = c.Customer_ID
        JOIN PRODUCTS p ON t.Product_ID = p.Product_ID
        JOIN CATEGORIES cat ON p.Category_ID = cat.Category_ID
        JOIN PAYMENT_METHODS pm ON t.Payment_ID = pm.Payment_ID
        LEFT JOIN DISCOUNTS d ON t.Discount_ID = d.Discount_ID
        WHERE t.Customer_ID = :customer_id
        FETCH FIRST :limite ROWS ONLY
    ";
} else {
    $sql = "
        SELECT 
            t.Transaction_ID AS TRANID,
            c.Customer_ID AS CUSTID,
            c.City AS CITY,
            p.Product_Name AS PNAME,
            cat.Category_Name AS CATNAME,
            t.Purchase_Date AS PDATE,
            t.Purchase_Amount AS PAMOUNT,
            pm.Payment_Method AS METHOD,
            d.Discount_Applied AS DISC
        FROM TRANSACTIONS t
        JOIN CUSTOMERS c ON t.Customer_ID = c.Customer_ID
        JOIN PRODUCTS p ON t.Product_ID = p.Product_ID
        JOIN CATEGORIES cat ON p.Category_ID = cat.Category_ID
        JOIN PAYMENT_METHODS pm ON t.Payment_ID = pm.Payment_ID
        LEFT JOIN DISCOUNTS d ON t.Discount_ID = d.Discount_ID
        FETCH FIRST :limite ROWS ONLY
    ";
}

$statement = oci_parse($conn, $sql);
oci_bind_by_name($statement, ":limite", $limite);
if ($customer_id) {
    oci_bind_by_name($statement, ":customer_id", $customer_id);
}


if (!oci_execute($statement)) {
    $e = oci_error($statement);
    die("Error en el SELECT: " . $e['message']);
}

while ($row = oci_fetch_array($statement, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['TRANID']) . "</td>";
    echo "<td>" . htmlspecialchars($row['CUSTID']) . "</td>";
    echo "<td>" . htmlspecialchars($row['CITY']) . "</td>";
    echo "<td>" . htmlspecialchars($row['PNAME']) . "</td>";
    echo "<td>" . htmlspecialchars($row['CATNAME']) . "</td>";
    echo "<td>" . htmlspecialchars($row['PDATE']) . "</td>";
    echo "<td>" . htmlspecialchars($row['PAMOUNT']) . "</td>";
    echo "<td>" . htmlspecialchars($row['METHOD']) . "</td>";
    echo "<td>" . htmlspecialchars($row['DISC']) . "</td>";
    echo "<td><form method=\"post\" action=\"/Tablas/TransaccionesCtrl/editarTrans.php\" onsubmit=\"return confirm('¿Estás seguro de editar este usuario?');\">
            <input type=\"hidden\" name=\"TRANID\" value=\"{$row['TRANID']}\">
            <button type=\"submit\" class=\"btn-warning\">Editar</button>
        </form>
         </td>";
    echo "<td> <form method=\"post\" action=\"/Tablas/TransaccionesCtrl/delete.php\" onsubmit=\"return confirm('¿Estás seguro de eliminar este usuario?');\">
            <input type=\"hidden\" name=\"TRANID\" value=\"{$row['TRANID']}\">
            <button type=\"submit\" class=\"btn-danger\">Eliminar</button>
            </form></td>";



    echo "</tr>";
}

oci_free_statement($statement);
oci_close($conn);
?>
            
        </table>
</br>
        </tbody>
            <a href="/tablas/TransaccionesCtrl/agregarTrans.php"><button class="btn btn-success" >Agregar Transaccion</button></a>
    </div>
</div>

<footer>
    <?php include("../footer.php"); ?>
</footer>
</body>
</html>
