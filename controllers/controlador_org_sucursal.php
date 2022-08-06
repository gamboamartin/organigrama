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

use gamboamartin\system\init;
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
        $r_alta =  parent::alta(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $inputs = (new org_sucursal_html($this->html_base))->genera_inputs_alta(controler: $this, link: $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar inputs',data:  $inputs, header: $header,ws:$ws);
        }
        return $r_alta;

    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true, bool $muestra_btn = true): array|string
    {
        $r_modifica =  parent::modifica(header: false,aplica_form:  false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $inputs = (new org_sucursal_html(html: $this->html_base))->inputs_org_sucursal(controlador_org_sucursal:$this);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al inicializar inputs',data:  $inputs, header: $header,ws:$ws);
        }


        return $r_modifica;
    }
    public function modifica_bd(bool $header, bool $ws): array|stdClass
    {
        $keys = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id');
        $_POST = (new init())->limpia_rows(keys: $keys,row:  $_POST);
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
