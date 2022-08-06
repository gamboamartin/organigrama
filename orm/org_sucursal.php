<?php
namespace models;
use base\orm\modelo;
use gamboamartin\errores\errores;

use PDO;

class org_sucursal extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'org_empresa'=>$tabla, 'dp_calle_pertenece'=>$tabla,
            'dp_calle' => 'dp_calle_pertenece', 'dp_colonia_postal'=>'dp_calle_pertenece',
            'dp_colonia'=>'dp_colonia_postal', 'dp_cp'=>'dp_colonia_postal', 'dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');
        $campos_obligatorios = array('org_empresa_id','dp_calle_pertenece_id','exterior','telefono_1');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
    public function alta_bd(): array|\stdClass
    {

        $keys = array('org_empresa_id','codigo','codigo_bis','fecha_inicio_operaciones','dp_calle_pertenece_id',
            'exterior','telefono_1');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        if(isset($this->registro['dp_pais_id'])){
            unset($this->registro['dp_pais_id']);
        }
        if(isset($this->registro['dp_estado_id'])){
            unset($this->registro['dp_estado_id']);
        }
        if(isset($this->registro['dp_municipio_id'])){
            unset($this->registro['dp_municipio_id']);
        }
        if(isset($this->registro['dp_cp_id'])){
            unset($this->registro['dp_cp_id']);
        }
        if(isset($this->registro['dp_colonia_postal_id'])){
            unset($this->registro['dp_colonia_postal_id']);
        }


        $r_alta_bd =  parent::alta_bd(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empresa', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }

}