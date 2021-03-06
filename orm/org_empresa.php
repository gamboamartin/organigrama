<?php
namespace models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_empresa;
use models\base\limpieza;
use PDO;
use stdClass;

class org_empresa extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false,'cat_sat_regimen_fiscal'=>$tabla,'dp_calle_pertenece'=>$tabla,
            'dp_colonia_postal'=>'dp_calle_pertenece','dp_cp'=>'dp_colonia_postal','dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado');
        $campos_obligatorios = array('codigo','nombre_comercial','rfc','razon_social');

        $no_duplicados = array('descripcion','codigo','descripcion_select','alias','codigo_bis','rfc','razon_social');


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,no_duplicados: $no_duplicados);
    }

    public function alta_bd(): array|stdClass
    {
        $keys = array('razon_social','rfc','codigo','nombre_comercial');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }


        $registro = (new limpieza())->init_org_empresa_alta_bd(registro:$this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar registro', data: $registro);
        }


        $this->registro = $registro;


        $r_alta_bd =  parent::alta_bd(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empresa', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }

    public function asigna_datos(controlador_org_empresa $controlador_org_empresa, int $registro_id){
        $registro = (new org_empresa($this->link))->registro(registro_id: $registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar registro empresa', data: $registro);
        }
        $controlador_org_empresa->rfc = $registro['org_empresa_rfc'];
        $controlador_org_empresa->razon_social = $registro['org_empresa_razon_social'];

        return $controlador_org_empresa;
    }
}