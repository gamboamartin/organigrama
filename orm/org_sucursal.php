<?php
namespace models;
use base\orm\modelo;
use PDO;

class org_sucursal extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'org_empresa'=>$tabla, 'dp_calle_pertenece'=>$tabla,
            'dp_calle' => 'dp_calle_pertenece', 'dp_colonia_postal'=>'dp_calle_pertenece',
            'dp_colonia'=>'dp_colonia_postal', 'dp_cp'=>'dp_colonia_postal', 'dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}