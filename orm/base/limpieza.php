<?php
namespace models\base;
use gamboamartin\errores\errores;

class limpieza{
    private errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    private function init_data_base_org_empresa(array $registro): array
    {
        if(!isset($registro['descripcion'])){
            $registro['descripcion'] = $registro['razon_social'];
        }
        if(!isset($this->registro['codigo_bis'])){
            $registro['codigo_bis'] = $registro['rfc'];
        }
        if(!isset($this->registro['descripcion_select'])){
            $registro['descripcion_select'] = $registro['descripcion'];
        }
        if(!isset($this->registro['alias'])){
            $registro['alias'] = $registro['descripcion'];
        }
        return $registro;
    }

    public function init_org_empresa_alta_bd(array $registro): array
    {
        $registro = $this->init_data_base_org_empresa(registro:$registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar registro', data: $registro);
        }


        $registro = $this->limpia_foraneas_org_empresa(registro:$registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar registro', data: $registro);
        }

        return $registro;
    }

    private function limpia_foraneas_org_empresa(array $registro): array
    {
        if(isset($registro['cat_sat_regimen_fiscal_id']) && (int)$registro['cat_sat_regimen_fiscal_id']===-1){
            unset($registro['cat_sat_regimen_fiscal_id']);
        }
        if(isset($registro['dp_calle_pertenece_id']) && (int)$registro['dp_calle_pertenece_id']===-1){
            unset($registro['dp_calle_pertenece_id']);
        }
        if(isset($registro['dp_calle_pertenece_entre2_id']) && (int)$registro['dp_calle_pertenece_entre2_id']===-1){
            unset($registro['dp_calle_pertenece_entre2_id']);
        }
        if(isset($registro['dp_calle_pertenece_entre1_id']) && (int)$registro['dp_calle_pertenece_entre1_id']===-1){
            unset($registro['dp_calle_pertenece_entre1_id']);
        }
        return $registro;
    }

}
