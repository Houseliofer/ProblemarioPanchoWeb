<?php

function validarCorreo($correo) {
    $partes = explode("@", $correo);

    // Verificar que el correo solo tenga una arroba
    if(count($partes) != 2) {
        return "DOMINIO INCORRECTO";
    }

    $local = $partes[0];
    $dominio = $partes[1];

    // Verificar que el nombre de dominio tenga solo letras, dígitos, guiones y puntos
    if(!preg_match("/^[a-zA-Z0-9-.]+$/", $dominio)) {
        return "DOMINIO INCORRECTO";
    }

    // Verificar que no haya puntos seguidos en el nombre de dominio
    if(strpos($dominio, "..") !== false) {
        return "DOMINIO INCORRECTO";
    }

    // Verificar que la parte local no esté vacía
    if(empty($local)) {
        return "USUARIO INCORRECTO";
    }

    // Verificar que no haya dos puntos seguidos en la parte local
    if(strpos($local, "..") !== false) {
        return "USUARIO INCORRECTO";
    }

    // Verificar que la parte local no contenga caracteres inválidos
    if(!preg_match('/^[a-zA-Z0-9-._#$%]+$/',$local)) {
        return "USUARIO INCORRECTO";
    }

    // El correo es válido, retornar el dominio
    return $dominio;
}

// Obtener el número de casos de prueba
$casos = intval(trim(fgets(STDIN)));

// Validar cada correo electrónico y mostrar el mensaje correspondiente
for($i = 0; $i < $casos; $i++) {
    $correo = trim(fgets(STDIN));
    $resultado = validarCorreo($correo);
    echo $resultado . "\n";
}

?>

