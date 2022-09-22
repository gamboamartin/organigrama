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
use gamboamartin\organigrama\models\org_empresa_clasificada;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template\html;
use html\org_empresa_clasificada_html;
use html\org_empresa_html;
use html\org_tipo_empresa_html;

use PDO;
use stdClass;

class controlador_org_empresa_clasificada extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_empresa_clasificada(link: $link);
        $html = new org_empresa_clasificada_html(html: $html);
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Empresa Clasificada';
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false, ws: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->inputs->select = new stdClass();

        $select = (new org_empresa_html($this->html_base))->select_org_empresa_id(cols:12,con_registros:true,id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }
        $this->inputs->select->org_empresa_id = $select;

        $select = (new org_tipo_empresa_html(html: $this->html_base))->select_org_tipo_empresa_id(cols:12,con_registros:true,id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }
        $this->inputs->select->org_tipo_empresa_id = $select;

        return $r_alta;
    }

}
