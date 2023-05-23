<?php
/*$host = "localhost";
$user = "root";
$password = "";
$database = "PagoServicios";*/

fscanf(STDIN, "%s", $s);
fscanf(STDIN, "%s", $u);
fscanf(STDIN, "%s", $p);
fscanf(STDIN, "%s", $bd);

// Crear la conexión a la base de datos
$conn = new mysqli($s, $u, $p, $bd);

$query = "DESCRIBE BD_PagoServ_Facturas";


$result = $conn->query($query);


$primaryKeys = array();
$foreignKeys = array();


while ($row = $result->fetch_assoc()) {
    $fieldName = $row['Field'];
    $fieldType = $row['Type'];

    if ($row['Key'] === 'PRI')
            $primaryKeys[] = "Nombre de llave primaria: $fieldName [$fieldType]";



    if ($row['Key'] === 'MUL') {
        $val = $row['Field'];

        $query2 = "SELECT * FROM 
             INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
         WHERE REFERENCED_TABLE_SCHEMA = '$bd' 
           AND TABLE_NAME = 'BD_PagoServ_Facturas' 
           AND REFERENCED_COLUMN_NAME IS NOT NULL
           AND COLUMN_NAME = '$val'";

        $result2 = $conn->query($query2);

        $row2 = $result2->fetch_assoc();

        $foreignKeyName = $val;
        $referencedTable = $row2['REFERENCED_TABLE_NAME'];
        $referencedField = $row2['REFERENCED_COLUMN_NAME'];

        $query3 = "Describe $referencedTable";
        $result3 = $conn->query($query3);
        $row3 = $result3->fetch_assoc();

        if ($row3['Key'] === 'PRI')
            $fieldType2 = $row3['Type'];
        else
            $fieldType2 = 'NULL';

        $foreignKeys[] = "Nombre:$foreignKeyName <=> Tabla Referenciada:$referencedTable <=> CampoForaneo:$referencedField <=> [$fieldType2]";
    }
}

function compareForeignKeys($a, $b) {
    // Extraer el nombre de la cadena "Nombre:X <=> ..."
    preg_match('/Nombre:(.*?)<=>/', $a, $aMatches);
    preg_match('/Nombre:(.*?)<=>/', $b, $bMatches);
    $nameA = $aMatches[1];
    $nameB = $bMatches[1];
    return strcmp($nameA, $nameB);
}

usort($foreignKeys, 'compareForeignKeys');

echo implode("\n", $primaryKeys) . "\n";
echo "Foraneas:\n";
echo implode("\n", $foreignKeys);


$conn->close();
?>

