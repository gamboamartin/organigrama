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
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use html\org_empresa_html;
use html\org_representante_asignado_html;
use html\org_representante_legal_html;
use models\org_representante_asignado;
use PDO;
use stdClass;

class controlador_org_representante_asignado extends system {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new org_representante_asignado(link: $link);
        $html = new org_representante_asignado_html();
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Actividades';

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false, ws: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->inputs->select = new stdClass();
        $select = (new org_representante_legal_html())->select_org_representante_legal_id(cols:12,con_registros:true,id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->org_representante_legal_id = $select;

        $select = (new org_empresa_html())->select_org_empresa_id(cols:12,con_registros:true,id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->org_empresa_id = $select;

        $in_fecha_inicio = (new org_representante_asignado_html())->input(cols: 6,row_upd:  new stdClass(),value_vacio:  true, campo: "Fecha Inicio");
        $in_fecha_fin = (new org_representante_asignado_html())->input(cols: 6,row_upd:  new stdClass(),value_vacio:  true, campo: "Fecha Fin");

        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar el input',data:  $in_fecha_inicio);
            print_r($error);
            die('Error');
        }

        $this->inputs->fecha_inicio = $in_fecha_inicio;
        $this->inputs->fecha_fin = $in_fecha_fin;


        return $r_alta;

    }

}
