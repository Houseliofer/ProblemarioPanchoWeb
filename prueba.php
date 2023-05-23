<?php
$linea = fgets(STDIN);
//$linea = "dato1 dato2 dato3 dato4"; // La línea que deseas dividir

// Divide la línea en un array utilizando el espacio como delimitador
$datos = explode(" ", $linea);

// Verifica si se obtuvieron los cuatro datos
if (count($datos) === 4) {
    // Accede a cada dato individualmente
    $dato1 = $datos[0];
    $dato2 = $datos[1];
    $dato3 = $datos[2];
    $dato4 = $datos[3];

    // Haz lo que necesites con los datos
    echo "Dato 1: " . $dato1 . "\n";
    echo "Dato 2: " . $dato2 . "\n";
    echo "Dato 3: " . $dato3 . "\n";
    echo "Dato 4: " . $dato4 . "\n";
} else {
    echo "La línea no contiene cuatro datos separados por espacios.";
}

