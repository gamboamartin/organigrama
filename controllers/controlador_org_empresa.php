<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\system\actions;
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
    public string $link_org_sucursal_alta_bd = '';
    public string $razon_social = '';
    public string $rfc = '';

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_empresa(link: $link);

        $html_ = new org_empresa_html(html: $html);
        $obj_link = new link_org_empresa($this->registro_id);

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Empresas';

        $generales = new generales();
        $this->link_org_sucursal_alta_bd = $generales->url_base;
        $this->link_org_sucursal_alta_bd .= 'index.php?seccion=org_empresa&accion=alta_sucursal_bd';
        $this->link_org_sucursal_alta_bd .= '&session_id='.$generales->session_id;
        $this->link_org_sucursal_alta_bd .= '&registro_id='.$this->registro_id;

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false); // TODO: Change the autogenerated stub
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
        $this->link->beginTransaction();

        $keys = array('dp_pais_id','dp_estado_id','dp_municipio_id','dp_cp_id','dp_colonia_postal_id');
        $_POST = (new init())->limpia_rows(keys: $keys,row:  $_POST);
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al limpiar datos',data:  $_POST, header: $header,ws:$ws);
        }

        $r_alta_bd = parent::alta_bd(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta empresa',data:  $r_alta_bd, header: $header,ws:$ws);
        }


        $this->link->commit();

        if($header){
            $retorno = (new actions())->retorno_alta_bd(registro_id:$r_alta_bd->registro_id,seccion: $this->tabla,
                siguiente_view: $r_alta_bd->siguiente_view);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al dar de alta registro', data: $r_alta_bd, header:  true,
                    ws: $ws);
            }
            header('Location:'.$retorno);
            exit;
        }
        if($ws){
            header('Content-Type: application/json');
            echo json_encode($r_alta_bd, JSON_THROW_ON_ERROR);
            exit;
        }

        return $r_alta_bd;
    }

    private function base(stdClass $params = new stdClass()): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false,aplica_form:  false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_modifica);
        }

        $inputs = (new org_empresa_html(html: $this->html_base))->inputs_org_empresa(
            controlador_org_empresa:$this, params: $params);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $inputs);
        }

        $registro = (new org_empresa($this->link))->asigna_datos(controlador_org_empresa: $this,
            registro_id: $this->registro_id);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar datos',data:  $registro);
        }
        $data = new stdClass();
        $data->template = $r_modifica;
        $data->inputs = $inputs;
        $data->registro = $registro;

        return $data;
    }

    public function cif(bool $header, bool $ws = false): array|stdClass
    {
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base;

    }

    /**
     * Obtiene los elementos necesarios para la ejecucion de la accion contacto donde se cargaran los elementos
     * de contacto
     * @param bool $header Si header muestra info en http
     * @param bool $ws Da salida json
     * @return array|stdClass
     */
    public function contacto(bool $header, bool $ws = false): array|stdClass
    {
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base;

    }

    public function identidad(bool $header, bool $ws = false): array|stdClass
    {
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base;

    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true,
                             bool $muestra_btn = true): array|string
    {
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base->template;
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

    /**
     * @author israel hernandez
     * @version v0.88.23
     * @version v0.1.0
     * @created 2022-08-01
     * @throws JsonException
     */
    public function modifica_cif(bool $header, bool $ws = false): array|stdClass
    {
        $keys_cifs[] = 'cat_sat_regimen_fiscal_id';
        $keys_cifs[] = 'fecha_inicio_operaciones';
        $keys_cifs[] = 'fecha_ultimo_cambio_sat';
        $keys_cifs[] = 'email_sat';

        $registro = array();
        foreach ($keys_cifs as $key_general){
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
     * @throws JsonException
     */
    public function modifica_generales(bool $header, bool $ws = false): array|stdClass
    {

        $siguiente_view = (new actions())->init_alta_bd();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener siguiente accion',data:  $siguiente_view,
                header: $header,ws:$ws);
        }

        $keys_generales[] = 'codigo';
        $keys_generales[] = 'rfc';
        $keys_generales[] = 'razon_social';
        $keys_generales[] = 'nombre_comercial';

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
        if($header){


            $retorno = (new actions())->retorno_alta_bd(registro_id: $r_modifica_bd->registro_id, seccion: $this->tabla,
                siguiente_view: $siguiente_view);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al modificar registro', data: $r_modifica_bd, header:  true,
                    ws: $ws);
            }
            header('Location:'.$retorno);
            exit;
        }
        if($ws){
            header('Content-Type: application/json');
            echo json_encode($r_modifica_bd, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_modifica_bd->siguiente_view = $siguiente_view;
        return $r_modifica_bd;



    }


    public function sucursales(bool $header, bool $ws = false): array|stdClass
    {

        $params = new stdClass();

        $params->codigo = new stdClass();
        $params->codigo->cols = 4;

        $params->fecha_inicio_operaciones = new stdClass();
        $params->fecha_inicio_operaciones->cols = 4;

        $base = $this->base(params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        $select = (new org_empresa_html(html: $this->html_base))->select_org_empresa_id(cols:12,con_registros: true,
            id_selected: $this->registro_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar select datos',data:  $select,
                header: $header,ws:$ws);
        }
        $this->inputs->select->org_empresa_id = $select;

        return $base;

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

        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base;

    }

}
