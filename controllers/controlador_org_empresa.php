<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace controllers;

use gamboamartin\errores\errores;
use gamboamartin\system\init;
use gamboamartin\system\system;
use html\cat_sat_regimen_fiscal_html;
use html\dp_calle_pertenece_html;
use html\dp_colonia_postal_html;
use html\dp_cp_html;
use html\dp_estado_html;
use html\dp_municipio_html;
use html\dp_pais_html;
use html\org_empresa_html;
use links\secciones\link_org_empresa;
use models\org_empresa;
use PDO;
use stdClass;

class controlador_org_empresa extends system {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new org_empresa(link: $link);
        $html = new org_empresa_html();
        $obj_link = new link_org_empresa($this->registro_id);

        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Empresas';

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false, ws: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->inputs->select = new stdClass();

        $select = (new cat_sat_regimen_fiscal_html())->select_cat_sat_regimen_fiscal_id(cols: 12, con_registros:true,
            id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->cat_sat_regimen_fiscal_id = $select;


        $select = (new dp_pais_html())->select_dp_pais_id(cols: 6, con_registros:true,
            id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->dp_pais_id = $select;


        $select = (new dp_estado_html())->select_dp_estado_id(cols: 6, con_registros:false,
            id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->dp_estado_id = $select;

        $select = (new dp_municipio_html())->select_dp_municipio_id(cols: 6, con_registros:false,
            id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->dp_municipio_id = $select;


        $select = (new dp_cp_html())->select_dp_cp_id(cols: 6, con_registros:false,
            id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->dp_cp_id = $select;


        $select = (new dp_colonia_postal_html())->select_dp_colonia_postal_id(cols: 12, con_registros:false,
            id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->dp_colonia_postal_id = $select;


        $select = (new dp_calle_pertenece_html())->select_dp_calle_pertenece_id(cols: 12, con_registros:false,
            id_selected:-1,link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }


        $this->inputs->select->dp_calle_pertenece_id = $select;



        $in_razon_social = (new org_empresa_html())->input_razon_social(cols: 12,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar input',data:  $in_razon_social);
            print_r($error);
            die('Error');
        }
        $this->inputs->razon_social = $in_razon_social;

        $in_rfc = (new org_empresa_html())->input_rfc(cols: 6,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar input',data:  $in_razon_social);
            print_r($error);
            die('Error');
        }
        $this->inputs->rfc = $in_rfc;

        $in_nombre_comercial = (new org_empresa_html())->input_nombre_comercial(cols: 12,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar input',data:  $in_nombre_comercial);
            print_r($error);
            die('Error');
        }
        $this->inputs->nombre_comercial = $in_nombre_comercial;


        return $r_alta;

    }

    public function alta_bd(bool $header, bool $ws = false): array|stdClass
    {

        $keys = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id');
        $_POST = (new init())->limpia_rows(keys: $keys,row:  $_POST);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al limpiar datos',data:  $_POST, header: $header,ws:$ws);
        }


        $r_alta_bd = parent::alta_bd(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al dar de alta empresa',data:  $r_alta_bd, header: $header,ws:$ws);
        }
        return $r_alta_bd;
    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true, bool $muestra_btn = true): array|string
    {
        $r_modifica =  parent::modifica($header, $ws, $breadcrumbs, $aplica_form, $muestra_btn); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $select = (new cat_sat_regimen_fiscal_html())->select_cat_sat_regimen_fiscal_id(cols:12,con_registros:true,
            id_selected:$this->row_upd->cat_sat_regimen_fiscal_id, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar select',data:  $select);
            print_r($error);
            die('Error');
        }

        $this->inputs->select = new stdClass();
        $this->inputs->select->cat_sat_regimen_fiscal_id = $select;

        return $r_modifica;
    }


}
