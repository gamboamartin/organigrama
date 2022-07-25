<?php
namespace models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class org_empresa extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false,'cat_sat_regimen_fiscal'=>$tabla,'dp_calle_pertenece'=>$tabla,
            'dp_colonia_postal'=>'dp_calle_pertenece','dp_cp'=>'dp_colonia_postal','dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');
        $campos_obligatorios = array('nombre_comercial','fecha_inicio_operaciones',
            'fecha_ultimo_cambio_sat','email_sat','rfc','razon_social');

        $no_duplicados = array('descripcion','codigo','descripcion_select','alias','codigo_bis','rfc','razon_social');


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,no_duplicados: $no_duplicados);
    }

    public function alta_bd(): array|stdClass
    {
        $keys = array('razon_social','rfc','codigo','nombre_comercial','fecha_inicio_operaciones',
            'fecha_ultimo_cambio_sat','email_sat');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        if(!isset($this->registros['descripcion'])){
            $this->registro['descripcion'] = $this->registro['razon_social'];
        }
        if(!isset($this->registros['codigo_bis'])){
            $this->registro['codigo_bis'] = $this->registro['rfc'];
        }
        if(!isset($this->registros['descripcion_select'])){
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
        }
        if(!isset($this->registros['alias'])){
            $this->registro['alias'] = $this->registro['descripcion'];
        }

        $r_alta_bd =  parent::alta_bd(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empresa', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }
}