<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use gamboamartin\errores\errores;

use gamboamartin\system\system;
use gamboamartin\template\html;

use html\org_sucursal_html;
use links\secciones\link_org_sucursal;
use models\org_sucursal;
use PDO;
use stdClass;

class controlador_org_sucursal extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new org_sucursal(link: $link);
        $html_ = new org_sucursal_html($html);
        $obj_link = new link_org_sucursal($this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Sucursales';

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false, ws: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }


        $inputs = (new org_sucursal_html($this->html_base))->genera_inputs_alta(controler: $this, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }
        return $r_alta;

    }


}
