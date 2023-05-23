<?php
$cadena = fgets(STDIN);
$cadena = trim($cadena);
$dato = explode(" ",$cadena);
$conexion1 = new mysqli($dato[0],$dato[1],$dato[2],$dato[3]);

$query1 = "SELECT 
       u.Usuario,
       u.Nombre,
	   u.Apellidos,
       f.fecha_Vencimiento,
       f.fecha_Pago
FROM $dato[3].Usuarios u 
JOIN $dato[3].BD_PagoServ_Facturas f on u.Usuario=f.id_Cliente
where f.fecha_Pago <= f.fecha_Vencimiento
and f.fecha_Pago!='0000-00-00'
order by u.Apellidos";

$resultado1 = $conexion1->query($query1);
$idActual="";
while ($fila1 = $resultado1->fetch_assoc()) {
    $nombreUsuario = $fila1['Nombre'];
    $apellidoUsuario = $fila1['Apellidos'];

    $queryConteo= "SELECT 
       count(*) as conta
FROM $dato[3].Usuarios u 
JOIN $dato[3].BD_PagoServ_Facturas f on u.Usuario=f.id_Cliente
where f.fecha_Pago <= f.fecha_Vencimiento
and f.fecha_Pago!='0000-00-00'
and u.Nombre = '$nombreUsuario'
and u.Apellidos = '$apellidoUsuario'";

    $resultado=$conexion1->query($queryConteo);
    $conta = $resultado->fetch_assoc();
    $query2 = "SELECT * 
FROM BD_DOMINO.Usuarios u
join BD_DOMINO.BD_Domino_Juegos j ON j.id_usuario=u.Usuario or j.id_invitado=u.Usuario
WHERE u.nombre = '$nombreUsuario'
and u.Apellidos = '$apellidoUsuario'";

    $queryConteo = "SELECT count(*) as conta
        FROM BD_DOMINO.Usuarios u
join BD_DOMINO.BD_Domino_Juegos j ON j.id_usuario=u.Usuario or j.id_invitado=u.Usuario
WHERE u.nombre = '$nombreUsuario'
    and u.Apellidos = '$apellidoUsuario'";

    $resultado=$conexion1->query($queryConteo);
    $conta2 = $resultado->fetch_assoc();

    $resultado2 = $conexion1->query($query2);
    $row = $resultado2->fetch_assoc();



    if ($resultado2->num_rows > 0) {
        $id = $row['Usuario'];
        if ($id !== $idActual) {
            echo $nombreUsuario." ".$apellidoUsuario.":"."BD_Servicios[".$conta['conta']."]:BD_Domino[".$conta2['conta']."]\n";
            $idActual = $id;
        }
    }
}

$conexion1->close();
