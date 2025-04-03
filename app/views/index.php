<?php
$conn = include_once __DIR__ . '/../libraries/Database.php';

$limite = 5;

$sql = "BEGIN SP_MOSTRAR_TRANSACCIONES_LIMITE_PHP(:limite, :cursor); END;";
$stmt = oci_parse($conn, $sql);

$cursor = oci_new_cursor($conn);

oci_bind_by_name($stmt, ':limite', $limite);
oci_bind_by_name($stmt, ':cursor', $cursor, -1, OCI_B_CURSOR);

oci_execute($stmt);
oci_execute($cursor);

// Mostrar resultados
echo "<table border='1'>";
echo "<tr>
        <th>ID</th><th>Cliente</th><th>Género</th><th>Ciudad</th>
        <th>Producto</th><th>Categoría</th><th>Fecha</th>
        <th>Monto</th><th>Pago</th><th>Descuento</th>
      </tr>";

while ($row = oci_fetch_assoc($cursor)) {
    echo "<tr>";
    foreach ($row as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

// Limpieza
oci_free_statement($stmt);
oci_free_statement($cursor);
oci_close($conn);
?>
