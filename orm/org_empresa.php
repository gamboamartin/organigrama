<?php
namespace gamboamartin\organigrama\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_empresa;
use PDO;
use stdClass;

class org_empresa extends modelo{
    public function __construct(PDO $link){
        $tabla = 'org_empresa';
        $columnas = array($tabla=>false,'cat_sat_regimen_fiscal'=>$tabla,'dp_calle_pertenece'=>$tabla,
            'dp_colonia_postal'=>'dp_calle_pertenece','dp_cp'=>'dp_colonia_postal','dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado','org_tipo_empresa'=>$tabla,
            'dp_colonia'=>'dp_colonia_postal','dp_calle'=>'dp_calle_pertenece','cat_sat_tipo_persona'=>$tabla);
        $campos_obligatorios = array('codigo','nombre_comercial','rfc','razon_social','org_tipo_empresa_id',
            'dp_calle_pertenece_id','cat_sat_regimen_fiscal_id','cat_sat_tipo_persona_id');

        $no_duplicados = array('descripcion','codigo','descripcion_select','alias','codigo_bis','rfc','razon_social');

        $tipo_campos['telefono_1'] = 'telefono_mx';
        $tipo_campos['telefono_2'] = 'telefono_mx';
        $tipo_campos['telefono_3'] = 'telefono_mx';
        $tipo_campos['rfc'] = 'rfc';

        $atributos_criticos[] = 'cat_sat_tipo_persona_id';

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, no_duplicados: $no_duplicados, tipo_campos: $tipo_campos,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Empresa';

    }

    public function alta_bd(): array|stdClass
    {

        if(!isset($this->registro['org_tipo_empresa_id'])){
            $org_tipo_empresa_id = (new org_tipo_empresa(link: $this->link))->id_predeterminado();
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener org_tipo_empresa_id predeterminada', data: $org_tipo_empresa_id);
            }
            $this->registro['org_tipo_empresa_id'] = $org_tipo_empresa_id;
        }

        if(!isset($this->registro['codigo'])){
            $org_tipo_empresa = (new org_tipo_empresa(link: $this->link))->registro(registro_id: $this->registro['org_tipo_empresa_id'], retorno_obj: true);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al obtener org_tipo_empresa', data: $org_tipo_empresa);
            }

            $codigo = $org_tipo_empresa->org_tipo_empresa_codigo;
            $codigo .= ' '.$this->registro['rfc'];
            $this->registro['codigo'] = $codigo;

        }

        $keys = array('razon_social','rfc','codigo','nombre_comercial','org_tipo_empresa_id');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        $registro = (new limpieza())->init_org_empresa_alta_bd(link: $this->link, registro:$this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar registro', data: $registro);
        }

        $this->registro = $registro;


        $r_alta_bd =  parent::alta_bd(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empresa', data: $r_alta_bd);
        }


        $r_alta_sucursal = $this->inserta_sucursal(org_empresa_id: $r_alta_bd->registro_id,registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta sucursal matriz', data: $r_alta_sucursal);
        }

        return $r_alta_bd;
    }

    public function asigna_datos(controlador_org_empresa $controlador_org_empresa, int $registro_id): array|controlador_org_empresa
    {
        $registro = $this->registro(registro_id: $registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar registro empresa', data: $registro);
        }
        $controlador_org_empresa->rfc = $registro['org_empresa_rfc'];
        $controlador_org_empresa->razon_social = $registro['org_empresa_razon_social'];

        return $controlador_org_empresa;
    }

    /**
     * Inserta una sucursal basada en empresa
     * @param int $org_empresa_id
     * @param array $registro
     * @return array|stdClass
     */
    private function inserta_sucursal(int $org_empresa_id, array $registro): array|stdClass
    {
        $org_sucursal_ins = (new limpieza())->org_sucursal_ins(link:$this->link,
            org_empresa_id: $org_empresa_id, org_empresa: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursal ins', data: $org_sucursal_ins);
        }

        $r_alta_sucursal = (new org_sucursal($this->link))->alta_registro(registro: $org_sucursal_ins);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta sucursal matriz', data: $r_alta_sucursal);
        }

        return $r_alta_sucursal;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {
        /**
         * REFACTORIZAR
         */
        $registro_previo = $this->registro(registro_id: $id, columnas_en_bruto: true, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener registro', data: $registro_previo);
        }
        if(!isset($registro['razon_social'])){
            $registro['razon_social'] = $registro_previo->razon_social;
        }

        if(!isset($registro['descripcion'])){
            $registro['descripcion'] = $registro['razon_social'];
        }
        if(!isset($registro['descripcion_select'])){
            $registro['descripcion_select'] = $registro['descripcion'];
        }
        if(!isset($registro['alias'])){
            $registro['alias'] = $registro['descripcion'];
        }


        $r_modifica =  parent::modifica_bd(registro: $registro, id: $id,reactiva:  $reactiva); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al modificar empresa', data: $r_modifica);
        }

        $org_sucursal_upd = (new limpieza())->org_sucursal_ins(link:$this->link,org_empresa_id: $id,
            org_empresa: $registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursal upd', data: $org_sucursal_upd);
        }

        $sucursal_matriz_id = (new org_sucursal($this->link))->sucursal_matriz_id(org_empresa_id:$id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursal matriz', data: $sucursal_matriz_id);
        }

        $r_modifica_suc = (new org_sucursal($this->link))->modifica_bd(registro: $org_sucursal_upd,
            id:  $sucursal_matriz_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al al modificar sucursal matriz', data: $r_modifica_suc);
        }



        return $r_modifica;
    }
}