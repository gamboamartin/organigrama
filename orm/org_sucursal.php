<?php
namespace gamboamartin\organigrama\models;
use base\orm\modelo;
use config\generales;
use gamboamartin\errores\errores;

use PDO;
use stdClass;

class org_sucursal extends modelo{
    public function __construct(PDO $link){
        $tabla = 'org_sucursal';
        $columnas = array($tabla=>false, 'org_empresa'=>$tabla, 'dp_calle_pertenece'=>$tabla,
            'dp_calle' => 'dp_calle_pertenece', 'dp_colonia_postal'=>'dp_calle_pertenece',
            'dp_colonia'=>'dp_colonia_postal', 'dp_cp'=>'dp_colonia_postal', 'dp_municipio'=>'dp_cp',
            'dp_estado'=>'dp_municipio','dp_pais'=>'dp_estado','org_tipo_sucursal'=>$tabla);
        $campos_obligatorios = array('descripcion','org_empresa_id', 'org_tipo_sucursal_id','dp_calle_pertenece_id');

        $tipo_campos['telefono_1'] = 'telefono_mx';
        $tipo_campos['telefono_2'] = 'telefono_mx';
        $tipo_campos['telefono_3'] = 'telefono_mx';
        $tipo_campos['org_tipo_sucursal_id'] = 'id';
        $tipo_campos['org_empresa_id'] = 'id';

        $no_duplicados[] = 'codigo';
        $no_duplicados[] = 'codigo_bis';

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios, columnas: $columnas,
            no_duplicados: $no_duplicados, tipo_campos: $tipo_campos);
    }
    public function alta_bd(): array|stdClass
    {


        $keys = array('org_empresa_id','codigo','codigo_bis');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys, registro: $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        $row = (new limpieza())->init_row_sucursal_alta(modelo: $this);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar row',data:  $row);
        }

        $keys = array('org_empresa_id','org_tipo_sucursal_id');
        $valida = $this->validacion->valida_ids(keys:$keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }


        $r_alta_bd =  parent::alta_bd(); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta empresa', data: $r_alta_bd);
        }
        return $r_alta_bd;
    }

    /**
     * Obtiene el conjunto de objetos relacionados de una sucursal
     * @param int $org_sucursal_id identificador de la sucursal
     * @return array|stdClass
     */
    public function data_sucursal_obj(int $org_sucursal_id): array|stdClass
    {


        $org_sucursal = $this->registro(registro_id: $org_sucursal_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursal',data:  $org_sucursal);
        }

        $org_tipo_sucursal = (new org_tipo_sucursal($this->link))->registro(
            registro_id: $org_sucursal->org_tipo_sucursal_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener tipo sucursal',data:  $org_tipo_sucursal);
        }
        $org_empresa = (new org_empresa($this->link))->registro(
            registro_id: $org_sucursal->org_empresa_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener $org_empresa',data:  $org_empresa);
        }


        $keys = array('org_tipo_empresa_id');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $org_empresa);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar $org_empresa',data:  $valida);
        }

        $org_tipo_empresa = (new org_tipo_empresa($this->link))->registro(
            registro_id: $org_empresa->org_tipo_empresa_id, columnas_en_bruto: true,retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener $org_empresa',data:  $org_tipo_empresa);
        }

        $data = new stdClass();
        $data->org_sucursal = $org_sucursal;
        $data->org_empresa = $org_empresa;
        $data->org_tipo_empresa = $org_tipo_empresa;
        $data->org_tipo_sucursal = $org_tipo_sucursal;

        return $data;
    }

    public function elimina_bd(int $id): array
    {
        $es_matriz = $this->es_matriz(org_sucursal_id: $id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar sucursal', data: $es_matriz);
        }
        if($es_matriz){
            return $this->error->error(mensaje: 'Error la sucursal no puede ser eliminada', data: $es_matriz);
        }
        $r_elimina_bd = parent::elimina_bd($id); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al eliminar sucursal', data: $r_elimina_bd);
        }
        return $r_elimina_bd;

    }

    /**
     * Verifica si una sucursal es matriz
     * @param int $org_sucursal_id Identificador de sucursal
     * @return bool|array
     */
    public function es_matriz(int $org_sucursal_id): bool|array
    {
        $filtro = array();
        $filtro['org_sucursal.id'] = $org_sucursal_id;
        $filtro['org_tipo_sucursal.id'] = (new generales())->tipo_sucursal_matriz_id;

        $existe = $this->existe(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursal matriz', data: $existe);
        }
        return $existe;

    }

    private function sucursal_matriz(int $org_empresa_id){
        $filtro = array();
        $filtro['org_empresa.id'] = $org_empresa_id;
        $filtro['org_tipo_sucursal.id'] = (new generales())->tipo_sucursal_matriz_id;

        $r_sucursal_matriz = (new org_sucursal($this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursal matriz', data: $r_sucursal_matriz);
        }
        if((int)$r_sucursal_matriz->n_registros > 1){
            return $this->error->error(mensaje: 'Error solo puede existir una sucursal matriz por empresa',
                data: $r_sucursal_matriz);
        }
        if((int)$r_sucursal_matriz->n_registros === 0){
            return $this->error->error(mensaje: 'Error no existe sucursal matriz de la empresa',
                data: $r_sucursal_matriz);
        }
        return $r_sucursal_matriz->registros[0];


    }

    public function sucursal_matriz_id(int $org_empresa_id): array|int
    {
        $sucursal_matriz = $this->sucursal_matriz(org_empresa_id: $org_empresa_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursal matriz', data: $sucursal_matriz);
        }
        return (int)$sucursal_matriz['org_sucursal_id'];

    }

    /**
     * Obtiene las sucursales de una empresa
     * @param int $org_empresa_id Identificador de empresa
     * @return array|stdClass
     * @version 0.188.33
     * @verfuncion 0.1.0
     * @functions org_sucursal->filtro_and
     */
    public function sucursales(int $org_empresa_id): array|stdClass
    {
        if($org_empresa_id <=0){
            return $this->error->error(mensaje: 'Error $org_empresa_id debe ser mayor a 0', data: $org_empresa_id);
        }
        $filtro['org_empresa.id'] = $org_empresa_id;
        $r_org_sucursal = $this->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener sucursales', data: $r_org_sucursal);
        }
        return $r_org_sucursal;
    }

}