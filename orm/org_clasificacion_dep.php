<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class org_clasificacion_dep extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_clasificacion_dep';
        $columnas = array($tabla=>false);

        $campos_obligatorios = array();
        $no_duplicados = array();
        $tipo_campos = array();

        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['codigo_bis'] = array('type' => 'inputs');

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            campos_view: $campos_view, no_duplicados: $no_duplicados, tipo_campos: $tipo_campos);
        $this->NAMESPACE = __NAMESPACE__;
    }

}