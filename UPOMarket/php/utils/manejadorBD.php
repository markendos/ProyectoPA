<?php

/* Desde este fichero controlamos las principales funciones de la base de datos. */

function openCon() {
//credenciales para la BD de Marcos
    $host = "localhost"; //localhost //85.137.192.11:3308
    $user = "serverProduccion"; //serverProduccion //upomarket
    $pas = "UpomarKet321!"; //UpomarKet321! //A2cMnUHsChA0DcZ8
    $db = "upomarket";
    try {
        $link = mysqli_connect($host, $user, $pas, $db);
    } catch (Exception $ex) {
        echo 'Excepcion: ', $ex->getMessage(), "\n";
    }
    mysqli_set_charset($link, "utf8");
    return $link;
}

function closeCon($link) {
    return mysqli_close($link);
}

//Si todo va bien devuelvo el resultado de la query, sino devuelvo false
function ejecutarConsulta($query) {
    try {
        $link = openCon();
        mysqli_set_charset($link, "utf8");
        $result = mysqli_query($link, $query);

        closeCon($link);
        return $result;
    } catch (Exception $ex) {
        echo 'Excepcion: ', $ex->getMessage(), "\n";
    }
}
