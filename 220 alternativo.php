<?php
// Lectura de los datos de conexión
$s = trim(fgets(STDIN));
$u = trim(fgets(STDIN));
$p = trim(fgets(STDIN));
$bd = trim(fgets(STDIN));

// Crear la conexión a la base de datos
$conn = new mysqli($s, $u, $p, $bd);

// Verificar errores de conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener la fecha base para el corte
$fechaBase = "2019-01-20";

// Consultar las facturas vencidas
$query = "SELECT f.id_Cliente, f.Monto, f.fecha_Vencimiento, s.Nombre, CONCAT(u.Apellidos, ' ', u.Nombre) AS Cliente
          FROM Facturas f
          INNER JOIN Servicios s ON f.id_Servicio = s.id
          INNER JOIN Usuarios u ON f.id_Cliente = u.Usuario
          WHERE f.fecha_Vencimiento <= '$fechaBase'
          ORDER BY Cliente ASC, f.fecha_Vencimiento ASC";

$result = $conn->query($query);

// Variables para el reporte
$totalAdeudos = 0;
$clienteActual = "";
$adeudoCliente = 0;

// Imprimir el reporte
echo "Total de Adeudos: $";
echo number_format($totalAdeudos, 2, '.', ',');
echo PHP_EOL;
echo PHP_EOL;

while ($row = $result->fetch_assoc()) {
    $cliente = $row['Cliente'];
    $monto = $row['Monto'];
    $fechaVencimiento = $row['fecha_Vencimiento'];
    $servicio = $row['Nombre'];

    if ($cliente !== $clienteActual) {
        if ($clienteActual !== "") {
            echo "Total de Adeudo: $";
            echo number_format($adeudoCliente, 2, '.', ',');
            echo PHP_EOL;
            echo PHP_EOL;
        }

        echo "Cliente: $cliente";
        echo PHP_EOL;
        $clienteActual = $cliente;
        $adeudoCliente = 0;
    }

    echo "Servicio: $servicio Total: $";
    echo number_format($monto, 2, '.', ',');
    echo " Fecha Venc.: $fechaVencimiento";
    echo PHP_EOL;

    $adeudoCliente += $monto;
    $totalAdeudos += $monto;
}

// Imprimir el total de adeudos para el último cliente
if ($clienteActual !== "") {
    echo "Total de Adeudo: $";
    echo number_format($adeudoCliente, 2, '.', ',');
    echo PHP_EOL;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
