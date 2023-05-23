<?php


fscanf(STDIN, "%s", $s);
fscanf(STDIN, "%s", $u);
fscanf(STDIN, "%s", $p);
fscanf(STDIN, "%s", $bd);

$conexion = new mysqli($s, $u, $p, $bd);

$sqlInvita = "SELECT concat(u.Nombre,' ',u.Apellidos) as nombre, COUNT(*) as jugadas
        FROM BD_Domino_Juegos   ju 
        JOIN Usuarios u ON u.Usuario=ju.id_usuario
        GROUP BY ju.id_usuario
        HAVING COUNT(*) = (
        SELECT MAX(total_jugadas)
        FROM ( SELECT COUNT(*) as total_jugadas
        FROM BD_Domino_Juegos  
        GROUP BY id_usuario
        ) j) ORDER BY u.Apellidos asc";
$resultadoInvita = mysqli_query($conexion, $sqlInvita);

$sqlInvitado = "SELECT concat(u.Nombre,' ',u.Apellidos) as nombre, COUNT(*) as jugadas 
            FROM BD_Domino_Juegos  ju 
            JOIN Usuarios u on u.Usuario=ju.id_invitado
            GROUP BY ju.id_invitado 
            HAVING COUNT(*) = 
                   ( SELECT MAX(total_jugadas) FROM 
                    ( SELECT COUNT(*) as total_jugadas 
                      FROM BD_Domino_Juegos  
                      GROUP BY id_invitado )j)
            ORDER BY u.Apellidos asc; ";
$resultadoInvitado = mysqli_query($conexion, $sqlInvitado);

echo "Invita\n";
while ($filaInvita = mysqli_fetch_array($resultadoInvita)) {

    echo $filaInvita['nombre'] . "\n";
}

echo "Invitado\n";
while ($filaInvitado = mysqli_fetch_array($resultadoInvitado)) {

    echo $filaInvitado['nombre'] . "\n";
}

mysqli_close($conexion);
