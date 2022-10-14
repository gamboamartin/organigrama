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

        $campos_view['org_empresa_id']['type'] = "selects";
        $campos_view['org_empresa_id']['model'] = new org_empresa($link);
        $campos_view['org_clasificacion_dep_id']['type'] = "selects";
        $campos_view['org_clasificacion_dep_id']['model'] = new org_clasificacion_dep($link);
        $campos_view['descripcion']['type'] = "inputs";
        $campos_view['descripcion']['cols'] = 6;
        $campos_view['descripcion']['place_holder'] = "Descripcion";
        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            campos_view: $campos_view, no_duplicados: $no_duplicados, tipo_campos: $tipo_campos);
    }

    public function alta_bd(): array|stdClass
    {
        if(!isset($this->registro['codigo_bis'])){
            $this->registro['codigo_bis'] = strtoupper($this->registro['codigo']);
        }

        if(!isset($this->registro['descripcion_select'])){
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
            $this->registro['descripcion_select'] .= $this->registro['codigo'];
        }
        if(!isset($this->registro['alias'])){
            $this->registro['alias'] = strtoupper($this->registro['descripcion_select']);
        }

        $r_alta_bd = parent::alta_bd(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta configuracion',data: $r_alta_bd);
        }
        return $r_alta_bd;
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

    public function departamentos_por_cls(int $org_clasificacion_dep_id): array|stdClass
    {
        if($org_clasificacion_dep_id <=0){
            return $this->error->error(mensaje: 'Error $org_empresa_id debe ser mayor a 0', data: $org_clasificacion_dep_id);
        }
        $filtro['org_clasificacion_dep.id'] = $org_clasificacion_dep_id;
        $r_org_departamento = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener departamentos', data: $r_org_departamento);
        }
        return $r_org_departamento;
    }
}