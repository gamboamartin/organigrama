<?php
require "init.php";
require 'vendor/autoload.php';

use base\conexion;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;

$con = new conexion();
$link = conexion::$link;


$init = (new _instalacion(link: $link));

$link->beginTransaction();

$foraneas = array();
$foraneas[] = 'cat_sat_regimen_fiscal_id';
$foraneas[] = 'dp_calle_pertenece_id';
$foraneas[] = 'org_tipo_empresa_id';
$foraneas[] = 'cat_sat_tipo_personal_id';

$result = (new _instalacion(link: $link))->foraneas(foraneas: $foraneas,table:  'org_empresa');

if(errores::$error){
    $link->rollBack();
    $error = (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);

    print_r($error);
    exit;
}

$link->commit();