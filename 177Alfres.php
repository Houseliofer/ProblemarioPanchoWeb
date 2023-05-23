<?php
$casos = trim(fgets(STDIN));
$iterador = 1;
$salida = "DOMINIO INCORRECTO" . PHP_EOL;
$email_is = '';
$regex = '/[a-z0-9-]+(\.[A-Za-z0-9-]+)@[^-][A-Za-z0-9-@-]+(\.[A-Za-z0-9-@-]+)(\.[A-Za-z]{2,})/';

//$localCorreo = preg_split('/^.*?(?=@)/', $correo, -1, PREG_SPLIT_NO_EMPTY);

$newLocal = '';
/*
 * validar que exista la parte local
 * validar que la parte local no tenga 2 puntos seguidos
 * validar que si el dominio tenga 2 @ se cuente el dominio desde el primero
 * */

do {
    $correo = trim(fgets(STDIN));

    //$string = 'The quick brown fox jumped over the lazy dog';
    $localPart = substr($correo, 0, strrpos($correo, '@'));
    //echo $localPart . "\n";

    /*    if( strpos($correo, '/^.*?(?=@)/') !== false) {
           //echo "\"bar\" exists in the haystack variable";
            echo "si jalo como se esperaba";
        } else {
            echo "else mano";
        }*/

    //$newLocal = (preg_split('/^.*?(?=@)/', $correo));
    //$newLocal = preg_split('/^.*?(?=@)/', $correo);
    //$equisde = $newLocal[0];
    //echo $equisde;

    //$string = 'This is a simple sting | badword is here';
    /*    $result = strtok($correo, '@');
        echo $result; // This is a simple sting*/

    /*    $var = explode('@', $correo);

        echo $var[0]; // This is a simple sting*/


    /*    if (!(preg_split('/^.*?(?=@)/', $correo))){

        }*/

    if (preg_match($regex, $correo, $email_is)) {
        //echo "si entro";
        if ((substr_count($correo, ('@')) <= 0)) {
            //incorrecto
        } else if ((substr_count($correo, (' ')) > 0)) {
            //incorrecto
        } else if ((substr_count($localPart,('.')) > 1)) {   //  puntos seguidos en la parte local

        } else {
            //correcto
            //$correo = substr($correo, );
            echo substr($correo, strrpos($correo, '@'));    //  arreglar
            $salida = "\nDOMINIO CORRECTO" . PHP_EOL;
        }
    } else {
        //echo $correo . " is an invalid email. Please try again.";
    }
    echo $salida;
    $iterador++;
} while ($iterador <= $casos)
?>