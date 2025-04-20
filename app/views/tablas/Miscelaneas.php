<!DOCTYPE html>
<html lang="en">
<?php include("../head.php"); ?>
<body>

    <?php include("menu.php"); ?>


<div class="container mt-4">
</br>
    <h1 class="text-center">Tablas</h1>

    <?php
    $conn = include_once __DIR__ . '/../../libraries/Database.php';

    $tablas = [
        'CATEGORIES' => ['ID' => 'CATEGORY_ID', 'NOMBRE' => 'CATEGORY_NAME'],
        'PRODUCTS' => ['ID' => 'PRODUCT_ID', 'NOMBRE' => 'PRODUCT_NAME', 'EXTRA' => 'CATEGORY_ID'],
        'PAYMENT_METHODS' => ['ID' => 'PAYMENT_ID', 'NOMBRE' => 'PAYMENT_METHOD'],
        'DISCOUNTS' => ['ID' => 'DISCOUNT_ID', 'NOMBRE' => 'DISCOUNT_APPLIED']
    ];

    foreach ($tablas as $tabla => $columnas) {
        echo "<h3 class='mt-5'>$tabla</h3>";
        echo "<a href='{$tabla}_agregar.php' class='btn btn-success mb-2'>Agregar</a>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr>";

        // Headers
        foreach ($columnas as $alias => $nombre) {
            echo "<th>" . htmlspecialchars($nombre) . "</th>";
        }
        echo "<th>Editar</th><th>Eliminar</th>";
        echo "</tr></thead><tbody>";

        $sql = "SELECT * FROM $tabla";
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            echo "<tr>";
            foreach ($columnas as $col) {
                echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
            }

            $id = $row[$columnas['ID']];

            echo "<td>
                    <form method='post' action='{$tabla}_editar.php'>
                        <input type='hidden' name='id' value='$id'>
                        <button class='btn btn-warning'>Editar</button>
                    </form>
                  </td>";

            echo "<td>
                    <form method='post' action='{$tabla}_eliminar.php' onsubmit='return confirm(\"¿Eliminar registro?\");'>
                        <input type='hidden' name='id' value='$id'>
                        <button class='btn btn-danger'>Eliminar</button>
                    </form>
                  </td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
        oci_free_statement($stmt);
    }

    oci_close($conn);
    ?>
</div>

<footer>
    <?php include("../footer.php"); ?>
</footer>
</body>
</html>
