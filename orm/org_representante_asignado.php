<?php
namespace models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use html\org_representante_asignado_html;
use PDO;
use stdClass;

class org_representante_asignado extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}