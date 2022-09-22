<?php
namespace gamboamartin\organigrama\models;
use base\orm\modelo;
use PDO;

class org_puesto extends modelo{
    public function __construct(PDO $link){
        $tabla = 'org_puesto';
        $columnas = array($tabla=>false,'org_tipo_puesto'=>$tabla,'org_departamento'=>$tabla,
            'org_empresa'=>'org_departamento');
        $campos_obligatorios = array('org_tipo_puesto_id','org_departamento_id');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}