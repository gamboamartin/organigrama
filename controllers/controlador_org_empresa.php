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
use html\org_empresa_html;
use JsonException;
use links\secciones\link_org_empresa;
use models\org_empresa;
use PDO;
use stdClass;

class controlador_org_empresa extends system{

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_empresa(link: $link);

        $html_ = new org_empresa_html(html: $html);
        $obj_link = new link_org_empresa($this->registro_id);

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Empresas';

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false, ws: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $inputs = (new org_empresa_html(html: $this->html_base))->genera_inputs_alta(controler: $this, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_alta;

    }

    public function alta_bd(bool $header, bool $ws = false): array|stdClass
    {

        $keys = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id');
        $_POST = (new init())->limpia_rows(keys: $keys,row:  $_POST);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al limpiar datos',data:  $_POST, header: $header,ws:$ws);
        }


        $r_alta_bd = parent::alta_bd(header: $header); // TODO: Change the autogenerated stub
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

        if(!isset($this->row_upd->cat_sat_regimen_fiscal_id)){
            $this->row_upd->cat_sat_regimen_fiscal_id = -1;
        }

        $org_empresa = $this->modelo->registro(registro_id: $this->registro_id,retorno_obj: true);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener registro',data:  $org_empresa);
            print_r($error);
            die('Error');
        }


        $keys_foraneas = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id',
            'dp_calle_pertenece_id','org_empresa_dp_calle_pertenece_entre1_id','org_empresa_dp_calle_pertenece_entre2_id');

        foreach ($keys_foraneas as $campo){
            if(is_null($org_empresa->$campo)){
                $org_empresa->$campo = '-1';
            }
        }


        $this->row_upd->dp_pais_id = $org_empresa->dp_pais_id;
        $this->row_upd->dp_estado_id = $org_empresa->dp_estado_id;
        $this->row_upd->dp_municipio_id = $org_empresa->dp_municipio_id;
        $this->row_upd->dp_cp_id = $org_empresa->dp_cp_id;
        $this->row_upd->dp_colonia_postal_id = $org_empresa->dp_colonia_postal_id;
        $this->row_upd->dp_calle_pertenece_id = $org_empresa->dp_calle_pertenece_id;
        $this->row_upd->dp_calle_pertenece_entre1_id = $org_empresa->org_empresa_dp_calle_pertenece_entre1_id;
        $this->row_upd->dp_calle_pertenece_entre2_id = $org_empresa->org_empresa_dp_calle_pertenece_entre2_id;


        $inputs = (new org_empresa_html(html: $this->html_base))->genera_inputs_modifica(controler: $this, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }


        return $r_modifica;
    }

    /**
     * @throws JsonException
     */
    public function modifica_generales(bool $header, bool $ws = false): array|stdClass
    {

        $keys_generales[] = 'codigo';
        $keys_generales[] = 'rfc';
        $keys_generales[] = 'razon_social';
        $keys_generales[] = 'nombre_comercial';
        $keys_generales[] = 'email_sat';
        $keys_generales[] = 'fecha_inicio_operaciones';
        $keys_generales[] = 'fecha_ultimo_cambio_sat';

        $registro = array();
        foreach ($keys_generales as $key_general){

            if(isset($_POST[$key_general])){
                $registro[$key_general] = $_POST[$key_general];
            }
        }
        $r_modifica_bd = $this->modelo->modifica_bd(registro: $registro, id: $this->registro_id);
        if(errores::$error){

            return $this->retorno_error(mensaje: 'Error al modificar generales',data:  $r_modifica_bd,
                header: $header,ws:$ws);
        }

        $_SESSION[$r_modifica_bd->salida][]['mensaje'] = $r_modifica_bd->mensaje.' del id '.$this->registro_id;
        $this->header_out(result: $r_modifica_bd, header: $header,ws:  $ws);

        return $r_modifica_bd;

    }

    /**
     * Obtiene los elementos necesarios para la ejecucion de la accion ubicacion donde se cargaran los elementos
     * de la ubicacion
     * @param bool $header Si header muestra info en http
     * @param bool $ws Da salida json
     * @return array|stdClass
     */
    public function ubicacion(bool $header, bool $ws = false): array|stdClass
    {
        $org_empresa = $this->modelo->registro( registro_id: $this->registro_id,columnas_en_bruto: true,
            retorno_obj: true);
        if(errores::$error){

            return $this->retorno_error(mensaje: 'Error al obtener empresa',data:  $org_empresa,
                header: $header,ws:$ws);
        }

        $this->registro = $org_empresa;

        return $org_empresa;

    }

}
