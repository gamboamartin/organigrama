<?php
namespace gamboamartin\organigrama\controllers\base;
use gamboamartin\errores\errores;
use gamboamartin\system\init;
use gamboamartin\system\system;
use stdClass;

class empresas extends system{

    /**
     * Limpia los datos postales previos a un modifica bd
     * @return array
     * @version 0.230.35
     */
    PUBLIC function limpia_post_dp(): array
    {
        $keys = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id');
        $_POST = (new init())->limpia_rows(keys: $keys,row:  $_POST);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al limpiar datos',data:  $_POST);
        }
        return $_POST;
    }

    public function modifica_bd(bool $header, bool $ws): array|stdClass
    {
        $_POST = $this->limpia_post_dp();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al limpiar datos',data:  $_POST, header: $header,ws:$ws);
        }

        $r_modifica_bd = parent::modifica_bd(header: false, ws: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al modificar empresa',data:  $r_modifica_bd,
                header: $header,ws:$ws);
        }


        $this->header_out(result: $r_modifica_bd, header: $header,ws:  $ws);
        return $r_modifica_bd;
    }

}
