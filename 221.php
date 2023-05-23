<?php
$s = trim(fgets(STDIN));
$u = trim(fgets(STDIN));
$p = trim(fgets(STDIN));
$bd = trim(fgets(STDIN));

$conn = new mysqli($s, $u, $p, $bd);

$query = "SELECT s.Nombre AS Servicio, COUNT(f.id) AS Facturas, SUM(f.Monto) AS Monto
          FROM BD_PagoServ_Servicios s
          LEFT JOIN BD_PagoServ_Facturas f ON f.id_Servicio = s.id AND (f.fecha_Pago <= f.fecha_Vencimiento and f.fecha_Pago>=f.fecha_Emision)
          GROUP BY s.Nombre
          ORDER BY s.Nombre ASC, Monto asc ";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $servicio = $row['Servicio'];
    $facturas = $row['Facturas'];
    $monto = $row['Monto'];


    echo "$servicio:$facturas:$".number_format($monto, 2, '.', '');;
    echo "\n";
}

$conn->close();
?>
