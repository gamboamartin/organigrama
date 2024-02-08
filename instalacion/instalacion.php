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