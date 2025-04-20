<!DOCTYPE html>
<html lang="en">
<?php include("../head.php"); ?>
<body>
<header>
    <?php include("menu.php"); ?>
</header>

<div class="container mt-4">
    <h2 class="text-center mb-4">Clientes</h2>

    <form method="get" action="" class="mb-3">
        <label for="limite" class="form-label">Cantidad de clientes a mostrar:</label>
        <input type="number" id="limite" class="form-control" name="limite" value="<?= isset($_GET['limite']) ? $_GET['limite'] : 10 ?>" min="1">
        <button type="submit" class="btn btn-primary mt-2">Aplicar</button>
    </form>

    <a href="clientes_agregar.php" class="btn btn-success mb-3">Agregar Cliente</a>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Edad</th>
                <th>Género</th>
                <th>Ciudad</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = include_once __DIR__ . '/../../libraries/Database.php';
            $limite = isset($_GET['limite']) && is_numeric($_GET['limite']) ? intval($_GET['limite']) : 10;

            $sql = "SELECT * FROM CUSTOMERS FETCH FIRST :limite ROWS ONLY";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":limite", $limite);
            oci_execute($stmt);

            while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['CUSTOMER_ID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['AGE']) . "</td>";
                echo "<td>" . htmlspecialchars($row['GENDER']) . "</td>";
                echo "<td>" . htmlspecialchars($row['CITY']) . "</td>";
                echo "<td>
                        <form method='post' action='clientes_editar.php'>
                            <input type='hidden' name='id' value='{$row['CUSTOMER_ID']}'>
                            <button class='btn btn-warning'>Editar</button>
                        </form>
                      </td>";
                echo "<td>
                        <form method='post' action='clientes_eliminar.php' onsubmit='return confirm(\"¿Eliminar cliente?\");'>
                            <input type='hidden' name='id' value='{$row['CUSTOMER_ID']}'>
                            <button class='btn btn-danger'>Eliminar</button>
                        </form>
                      </td>";
                echo "</tr>";
            }

            oci_free_statement($stmt);
            oci_close($conn);
            ?>
        </tbody>
    </table>
</div>

<footer>
    <?php include("../footer.php"); ?>
</footer>
</body>
</html>
