<?php

function openCon() {
//credenciales para la BD de Marcos
    $host = "85.137.192.11:3308";
    $user = "upomarket";
    $pas = "A2cMnUHsChA0DcZ8";
    $db = "upomarket";
    try {
        $link = mysqli_connect($host, $user, $pas, $db);
    } catch (Exception $ex) {
        echo 'Excepcion: ', $ex->getMessage(), "\n";
    }
    return $link;
}

function closeCon($link) {
    return mysqli_close($link);
}

//Si todo va bien devuelvo el resultado de la query, sino devuelvo false
function ejecutarConsulta($query) {
    try {
        $link = openCon();
        $result = mysqli_query($link, $query);

        closeCon($link);
        return $result;
    } catch (Exception $ex) {
        echo 'Excepcion: ', $ex->getMessage(), "\n";
    }
}