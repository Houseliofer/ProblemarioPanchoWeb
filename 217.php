<?php


fscanf(STDIN, "%s", $s);
fscanf(STDIN, "%s", $u);
fscanf(STDIN, "%s", $p);
fscanf(STDIN, "%s", $bd);

$conexion = new mysqli($s, $u, $p, $bd);

$sql = "SELECT CONCAT(u.Nombre, ' ', u.Apellidos) AS nombre, SUM(j.puntos) AS total_puntos
        FROM BD_Domino_Juegos j
        JOIN Usuarios u ON j.ganador = u.Usuario
        WHERE j.id_estatus = 1
        GROUP BY u.Nombre, u.Apellidos
        ORDER BY total_puntos DESC
        LIMIT 1";
$resultado = mysqli_query($conexion, $sql);

if ($fila = mysqli_fetch_array($resultado)) {
    $nombreJugador = $fila['nombre'];
    $totalPuntos = $fila['total_puntos'];

    // Imprimir el resultado
    echo $nombreJugador . " " . $totalPuntos;
}

mysqli_close($conexion);


