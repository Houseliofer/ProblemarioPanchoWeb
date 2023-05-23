<?php
fscanf(STDIN, "%s", $s);
fscanf(STDIN, "%s", $u);
fscanf(STDIN, "%s", $p);
fscanf(STDIN, "%s", $bd);

$conexion = new mysqli($s, $u, $p, $bd);

$sql = "SELECT j.id, CONCAT(u1.Nombre, ' ', u1.Apellidos) AS Invitador, CONCAT(u2.Nombre, ' ', u2.Apellidos) AS Invitado, j.secuencia
        FROM BD_Domino_Juegos j
        INNER JOIN Usuarios u1 ON j.id_usuario = u1.Usuario
        INNER JOIN Usuarios u2 ON j.id_invitado = u2.Usuario
        where j.id_estatus='1' or j.id_estatus='3'";

$resultado = $conexion->query($sql);

while ($fila = $resultado->fetch_assoc()) {
    $idJuego = $fila['id'];
    $invitador = $fila['Invitador'];
    $invitado = $fila['Invitado'];
    $secuencia = $fila['secuencia'];

    $mensaje = "";
    if (validarFichasDuplicadas($secuencia)) {
        $mensaje = "Ficha Duplicada";
    } elseif (!validarSecuencia($secuencia)) {
        $mensaje = "Secuencia Mal";
    }

    if ($mensaje != "") {
        echo $idJuego . ":" . $invitador . ":" . $invitado . ":" . $mensaje . "\n";
    }
}


$conexion->close();

function validarSecuencia($secuencia)
{
    $fichas = explode(" ", $secuencia);
    $prev = "";
    foreach ($fichas as $ficha) {
        $partes = explode(":", $ficha);
        if (count($partes) != 2)
            return false;
        $num1 = $partes[0];
        $num2 = $partes[1];
        if (!is_numeric($num1) || !is_numeric($num2) || $num1 < 0 || $num1 > 6 || $num2 < 0 || $num2 > 6)
            return false;
        if ($prev != "") {
            $partesPrev = explode(":", $prev);
            if ($partesPrev[1] != $num1)
                return false;
        }
        $prev = $ficha;
    }
    return true;
}

function validarFichasDuplicadas($secuencia)
{
    $fichas = explode(" ", $secuencia);
    $fichasDuplicadas = array();
    foreach ($fichas as $ficha) {
        $inversa = invertirFicha($ficha);
        if (in_array($ficha, $fichasDuplicadas) || in_array($inversa, $fichasDuplicadas)) {
            return true;
        }
        $fichasDuplicadas[] = $ficha;
    }
    return false;
}

// Función para invertir una ficha
function invertirFicha($ficha)
{
    $partes = explode(":", $ficha);
    $num1 = $partes[0];
    $num2 = $partes[1];
    return $num2 . ":" . $num1;
}
?>


