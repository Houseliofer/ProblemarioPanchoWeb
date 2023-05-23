<?php

$cadena = fgets(STDIN);
$cadena = trim($cadena);
$dato = explode(" ",$cadena);

$conexion = new mysqli($dato[0],$dato[1],$dato[2],$dato[3]);

while ($linea = fgets(STDIN)) {
    $linea = trim($linea);

    if (empty($linea))
        break;

    $dato = explode(" ", $linea);

    $usu = $dato[0];
    $pwd = $dato[1];

    $query = "select concat(u.Nombre,' ',u.Apellidos) as nombres,
                f.id_Cliente,
                f.id_FormaPago,
                f.Ref_Bancaria 
              from Usuarios u 
              JOIN BD_PagoServ_Facturas f on u.Usuario=f.id_Cliente 
              where f.id_FormaPago=2 
              and u.Clave=PASSWORD('$pwd') 
              and u.Usuario='$usu' 
              order by f.id_Cliente asc, nombres asc, f.Ref_Bancaria asc";

    $resultado = $conexion->query($query);
    $idActual="";

    while($resu = $resultado->fetch_assoc()){
        $nombres = $resu['nombres'];
        $id = $resu['id_Cliente'];
        $tarje = $resu['Ref_Bancaria'];
        if ($id !== $idActual) {
            echo $id.":".$nombres;
            $idActual = $id;
        }
        echo ":".$tarje;
    }
    echo "\n";
}



$conexion->close();
?>
