<?php
namespace gamboamartin\organigrama\instalacion;

use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;

class instalacion
{
    final public function instala(PDO $link)
    {
        $init = (new _instalacion(link: $link));
        $foraneas = array();
        $foraneas[] = 'cat_sat_regimen_fiscal_id';
        $foraneas[] = 'dp_calle_pertenece_id';
        $foraneas[] = 'org_tipo_empresa_id';
        $foraneas[] = 'cat_sat_tipo_persona_id';

        $result = $init->foraneas(foraneas: $foraneas,table:  'org_empresa');

        if(errores::$error){
           return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        return $result;

    }

}