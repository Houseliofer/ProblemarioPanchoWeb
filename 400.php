<?php

// Función para generar un dato aleatorio de tipo char
function generarDatoChar($tipoCampo) {
    if ($tipoCampo === "nombre") {
        $nombres = ["Juan", "Ana", "Maria", "Luisa", "Luis", "Pedro", "Angel", "Carla", "Alicia", "Josefina", "Fernando"];
        return $nombres[array_rand($nombres)];
    } elseif ($tipoCampo === "apellido") {
        $apellidos = ["Lopez", "Perez", "Martinez", "Jimenez", "Gutierrez", "Vera", "Ortega", "Castillo", "Mireles", "Frias", "Morales", "Mejia", "Garcia"];
        return $apellidos[array_rand($apellidos)];
    } elseif ($tipoCampo === "telefono") {
        $lada = "464";
        $numero = substr(str_shuffle("0123456789"), 0, 7);
        return $lada . " " . $numero;
    } else {
        return null;
    }
}

// Función para generar un dato aleatorio de tipo int
function generarDatoInt($rangoInferior, $rangoSuperior) {
    return rand($rangoInferior, $rangoSuperior);
}

// Función para generar un dato aleatorio de tipo date
function generarDatoDate($fechaMenor, $fechaMayor) {
    $fechaMenorTimestamp = strtotime($fechaMenor);
    $fechaMayorTimestamp = strtotime($fechaMayor);
    $randomTimestamp = mt_rand($fechaMenorTimestamp, $fechaMayorTimestamp);
    return date("Y-m-d", $randomTimestamp);
}

// Obtener los datos de entrada
$nombreTabla = "Empleados";
$numRegistros = 2;
$numCampos = 4;
$campos = [
    ["Nombre", "char", "nombre"],
    ["Telefono", "char", "telefono", "464"],
    ["Edad", "int", "18", "34"],
    ["Referencias", "char"],
];

// Generar el script de SQL
$sqlScript = "DROP TABLE IF EXISTS '$nombreTabla'.\n";
$sqlScript .= "CREATE TABLE '$nombreTabla'.\n";
$sqlScript .= "`id` int auto_increment,\n";

for ($i = 0; $i < $numCampos; $i++) {
    $nombreCampo = $campos[$i][0];
    $tipoCampo = $campos[$i][1];
    $sqlScript .= "`$nombreCampo` $tipoCampo";

    // Agregar tamaño máximo para campos char
    if ($tipoCampo === "char") {
        $sqlScript .= "(255)";
    }

    $sqlScript .= " NOT NULL";

    // Generar datos aleatorios para los campos
    if ($tipoCampo === "char") {
        $datosExtras = array_slice($campos[$i], 2);
        $datoAleatorio = generarDatoChar($datosExtras);
        if ($datoAleatorio !== null) {
            $sqlScript .= " DEFAULT '$datoAleatorio'";
        } else {
            $sqlScript .= " DEFAULT NULL";
        }
    } elseif ($tipoCampo === "int") {
        $rangoInferior = intval($campos[$i][2]);
        $rangoSuperior = intval($campos[$i][3]);
        $datoAleatorio = generarDatoInt($rangoInferior, $rangoSuperior);
        $sqlScript .= " DEFAULT $datoAleatorio";
    } elseif ($tipoCampo === "date") {
        $fechaMenor = $campos[$i][2];
        $fechaMayor = $campos[$i][3];
        $datoAleatorio = generarDatoDate($fechaMenor, $fechaMayor);
        $sqlScript .= " DEFAULT '$datoAleatorio'";
    }

    $sqlScript .= ",\n";
}

$sqlScript .= "PRIMARY KEY (`id`)\n";
$sqlScript .= ") AUTO_INCREMENT=1;\n";

$sqlScript .= "INSERT INTO `$nombreTabla` (";
for ($i = 0; $i < $numCampos - 1; $i++) {
    $nombreCampo = $campos[$i][0];
    $sqlScript .= "`$nombreCampo`, ";
}
$nombreCampo = $campos[$numCampos - 1][0];
$sqlScript .= "`$nombreCampo`)\n";
$sqlScript .= "VALUES\n";

for ($j = 0; $j < $numRegistros; $j++) {
    $sqlScript .= "(";
    for ($i = 0; $i < $numCampos - 1; $i++) {
        $tipoCampo = $campos[$i][1];
        if ($tipoCampo === "char") {
            $datosExtras = array_slice($campos[$i], 2);
            $datoAleatorio = generarDatoChar(...$datosExtras);
            if ($datoAleatorio !== null) {
                $sqlScript .= "'$datoAleatorio', ";
            } else {
                $sqlScript .= "NULL, ";
            }
        } elseif ($tipoCampo === "int") {
            $rangoInferior = intval($campos[$i][2]);
            $rangoSuperior = intval($campos[$i][3]);
            $datoAleatorio = generarDatoInt($rangoInferior, $rangoSuperior);
            $sqlScript .= "$datoAleatorio, ";
        } elseif ($tipoCampo === "date") {
            $fechaMenor = $campos[$i][2];
            $fechaMayor = $campos[$i][3];
            $datoAleatorio = generarDatoDate($fechaMenor, $fechaMayor);
            $sqlScript .= "'$datoAleatorio', ";
        }
    }
    $tipoCampo = $campos[$numCampos - 1][1];
    if ($tipoCampo === "char") {
        $datosExtras = array_slice($campos[$numCampos - 1], 2);
        $datoAleatorio = generarDatoChar($datosExtras);
        if ($datoAleatorio !== null) {
            $sqlScript .= "'$datoAleatorio'";
        } else {
            $sqlScript .= "NULL";
        }
    } elseif ($tipoCampo === "char") {
        $datosExtras = array_slice($campos[$numCampos - 1], 2);
        $datoAleatorio = generarDatoChar($datosExtras);
        if ($datoAleatorio !== null) {
            $sqlScript .= "'$datoAleatorio'";
        } else {
            $sqlScript .= "NULL";
        }
    } elseif ($tipoCampo === "int") {
        $rangoInferior = intval($campos[$numCampos - 1][2]);
        $rangoSuperior = intval($campos[$numCampos - 1][3]);
        $datoAleatorio = generarDatoInt($rangoInferior, $rangoSuperior);
        $sqlScript .= "$datoAleatorio";
    } elseif ($tipoCampo === "date") {
        $fechaMenor = $campos[$numCampos - 1][2];
        $fechaMayor = $campos[$numCampos - 1][3];
        $datoAleatorio = generarDatoDate($fechaMenor, $fechaMayor);
        $sqlScript .= "'$datoAleatorio'";
    }

    $sqlScript .= ")";
    if ($j < $numRegistros - 1) {
        $sqlScript .= ",\n";
    }
}

echo $sqlScript;
?>

