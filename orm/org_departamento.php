<?php
namespace gamboamartin\organigrama\models;
use base\orm\modelo;

use gamboamartin\errores\errores;
use PDO;
use stdClass;

class org_departamento extends modelo{
    public function __construct(PDO $link){
        $tabla = 'org_departamento';
        $columnas = array($tabla=>false, 'org_empresa'=>$tabla,'org_clasificacion_dep'=>$tabla);

        $campos_obligatorios = array('org_clasificacion_dep_id');
        $no_duplicados = array();
        $tipo_campos = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            no_duplicados: $no_duplicados, tipo_campos: $tipo_campos);
    }


    public function departamentos(int $org_empresa_id): array|stdClass
    {
        if($org_empresa_id <=0){
            return $this->error->error(mensaje: 'Error $org_empresa_id debe ser mayor a 0', data: $org_empresa_id);
        }
        $filtro['org_empresa.id'] = $org_empresa_id;
        $r_org_departamento = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener departamentos', data: $r_org_departamento);
        }
        return $r_org_departamento;
    }
}