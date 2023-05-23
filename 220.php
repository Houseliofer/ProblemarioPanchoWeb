<?php
$s = trim(fgets(STDIN));
$u = trim(fgets(STDIN));
$p = trim(fgets(STDIN));
$bd = trim(fgets(STDIN));

$conn = new mysqli($s, $u, $p, $bd);

$fechaBase = "2019-01-20";

$query = "SELECT f.id_Cliente as id, f.Monto, f.fecha_Vencimiento, s.Nombre, CONCAT(u.Apellidos, ' ', u.Nombre) AS Cliente
          FROM Facturas f
          INNER JOIN Servicios s ON f.id_Servicio = s.id
          INNER JOIN Usuarios u ON f.id_Cliente = u.Usuario
          WHERE f.fecha_Vencimiento <= '$fechaBase'
          AND f.fecha_pago = '0000-00-00'
          ORDER BY Cliente ASC, f.fecha_Vencimiento ASC";


$result = $conn->query($query);

$totalAdeudos = 0;
$clienteActual = "";

while ($row = $result->fetch_assoc()) {
    $totalAdeudos += $row['Monto'];
}

$result = $conn->query($query);
echo "Total de Adeudos: $";
echo number_format($totalAdeudos, 2, '.', ',');

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $cliente = $row['Cliente'];
    $monto = $row['Monto'];
    $fechaVencimiento = $row['fecha_Vencimiento'];
    $servicio = $row['Nombre'];

    $query2 = "SELECT Sum(Monto) as total
          FROM Facturas f
          INNER JOIN Usuarios u ON f.id_Cliente = u.Usuario
          where f.id_Cliente = '$id'
          AND f.fecha_Vencimiento <= '$fechaBase'
          AND f.fecha_pago = '0000-00-00'";

    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();

    if ($cliente !== $clienteActual) {
        echo "\n";
        echo "Cliente: $cliente"." Total de Adeudo: $".number_format($row2['total'], 2, '.', ',');
        $clienteActual = $cliente;
    }
    echo "\n";
    echo "Servicio: $servicio Total: $". number_format($monto, 2, '.', ',').
          " Fecha Venc.: $fechaVencimiento";


}

$conn->close();
?>

