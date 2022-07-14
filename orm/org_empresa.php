<?php
namespace models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class org_empresa extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false,'cat_sat_regimen_fiscal'=>$tabla);
        $campos_obligatorios = array('cat_sat_regimen_fiscal_id','nombre_comercial','fecha_inicio_operaciones',
            'fecha_ultimo_campo_sat','dp_calle_pertenece_id','exterior','dp_calle_pertenece_entre1_id',
            'dp_calle_pertenece_entre2_id','email_sat','telefono_1','rfc','razon_social');

        $no_duplicados = array('descripcion','codigo','descripcion_select','alias','codigo_bis','rfc','razon_social');


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,no_duplicados: $no_duplicados);
    }

    public function alta_bd(): array|stdClass
    {
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