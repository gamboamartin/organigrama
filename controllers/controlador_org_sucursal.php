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
use gamboamartin\organigrama\controllers\base\empresas;
use gamboamartin\template\html;
use html\org_sucursal_html;
use links\secciones\link_org_sucursal;
use models\org_sucursal;
use PDO;
use stdClass;

class controlador_org_sucursal extends empresas {
    protected int $org_empresa_id = -1;

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new org_sucursal(link: $link);
        $html_ = new org_sucursal_html($html);
        $obj_link = new link_org_sucursal($this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Sucursales';

    }

    public function alta(bool $header, bool $ws = false, bool $org_empresa_id_disabled = false): array|string
    {
        $r_alta =  parent::alta(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $inputs = (new org_sucursal_html($this->html_base))->genera_inputs_alta(controler: $this, link: $this->link,
            org_empresa_id: $this->org_empresa_id,org_empresa_id_disabled: $org_empresa_id_disabled);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar inputs',data:  $inputs, header: $header,ws:$ws);
        }
        return $r_alta;

    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true,
                             bool $muestra_btn = true): array|string
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



}
