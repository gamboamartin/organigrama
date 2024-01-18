<?php
namespace gamboamartin\organigrama\instalacion;

use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{
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

        return $result;

    }

}