<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;

use PDO;


class org_tipo_sucursal extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_tipo_sucursal';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();


        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['descripcion'] = array('type' => 'inputs');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;
    }

}