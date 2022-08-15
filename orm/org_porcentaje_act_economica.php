<?php
namespace models;
use base\orm\modelo;
use PDO;

class org_porcentaje_act_economica extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false,'org_empresa'=>$tabla,'cat_sat_actividad_economica'=>$tabla);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}