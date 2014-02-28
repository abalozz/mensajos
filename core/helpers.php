<?php

/**
 * Funciones útiles
 */

/**
 * Muestra un error 404 indicando que la página no fue encontrada.
 */
function error_404()
{
    die(':(<br>La página que estabas buscando no existe.<br>Error 404');
}

function encrypt($password)
{
    return sha1($password);
}