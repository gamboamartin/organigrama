<?php
namespace gamboamartin\organigrama\instalacion;

use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion


{

    private function _add_org_logo(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $create = (new _instalacion(link: $link))->create_table_new(table: 'org_logo');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create table', data:  $create);
        }
        $out->create = $create;
        $foraneas = array();
        $foraneas['org_empresa_id'] = new stdClass();
        $foraneas['doc_documento_id'] = new stdClass();

        $foraneas_r = (new _instalacion(link:$link))->foraneas(foraneas: $foraneas,table:  'org_logo');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }
        $out->foraneas_r = $foraneas_r;

        /*$campos = new stdClass();
        $campos->fecha_pago = new stdClass();
        $campos->fecha_pago->tipo_dato = 'DATETIME';
        $campos->fecha_pago->default = '1900-01-01';

        $campos->comprobante_sello = new stdClass();
        $campos->comprobante_sello->tipo_dato = 'longblob';

        $campos->comprobante_certificado = new stdClass();
        $campos->comprobante_certificado->tipo_dato = 'longblob';

        $campos->comprobante_no_certificado = new stdClass();

        $campos->complemento_tfd_sl = new stdClass();
        $campos->complemento_tfd_sl->tipo_dato = 'longblob';

        $campos->complemento_tfd_fecha_timbrado = new stdClass();
        $campos->complemento_tfd_no_certificado_sat = new stdClass();
        $campos->complemento_tfd_rfc_prov_certif = new stdClass();

        $campos->complemento_tfd_sello_cfd = new stdClass();
        $campos->complemento_tfd_sello_cfd->tipo_dato = 'longblob';

        $campos->complemento_tfd_sello_sat = new stdClass();
        $campos->complemento_tfd_sello_sat->tipo_dato = 'longblob';

        $campos->uuid = new stdClass();
        $campos->complemento_tfd_tfd = new stdClass();

        $campos->cadena_complemento_sat = new stdClass();
        $campos->cadena_complemento_sat->tipo_dato = 'longblob';

        $result = (new _instalacion(link: $link))->add_columns(campos: $campos,table:  'fc_receptor_email');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar campos', data:  $result);
        }
        $out->columnas = $result;*/



        return $out;

    }

    private function org_logo(PDO $link): array|stdClass
    {
        $create = $this->_add_org_logo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar create', data:  $create);
        }

        return $create;

    }
    final public function instala(PDO $link)
    {
        $init = (new _instalacion(link: $link));
        $foraneas = array();
        $foraneas['cat_sat_regimen_fiscal_id'] = new stdClass();
        $foraneas['dp_calle_pertenece_id'] =  new stdClass();
        $foraneas['org_tipo_empresa_id'] = new stdClass();
        $foraneas['cat_sat_tipo_persona_id'] = new stdClass();

        $result = $init->foraneas(foraneas: $foraneas,table:  'org_empresa');

        if(errores::$error){
           return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }


        $org_logo = $this->org_logo(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar org_logo', data:  $org_logo);
        }
        

        return $result;

    }

}