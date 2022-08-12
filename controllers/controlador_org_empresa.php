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
use gamboamartin\system\actions;
use gamboamartin\system\init;
use gamboamartin\system\system;

use gamboamartin\template\html;
use html\dp_calle_html;
use html\dp_colonia_html;
use html\dp_cp_html;
use html\dp_estado_html;
use html\dp_municipio_html;
use html\org_empresa_html;
use html\org_sucursal_html;
use html\org_tipo_sucursal_html;
use JsonException;
use links\secciones\link_org_empresa;
use links\secciones\link_org_sucursal;
use models\dp_calle;
use models\dp_calle_pertenece;
use models\dp_colonia;
use models\dp_colonia_postal;
use models\dp_cp;
use models\dp_estado;
use models\dp_municipio;
use models\org_empresa;
use models\org_sucursal;
use models\org_tipo_sucursal;
use PDO;
use stdClass;

class controlador_org_empresa extends system{
    public string $link_org_sucursal_alta_bd = '';
    public string $razon_social = '';
    public string $rfc = '';
    public int $org_empresa_id = -1;
    public stdClass $sucursales ;

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_empresa(link: $link);

        $html_ = new org_empresa_html(html: $html);
        $obj_link = new link_org_empresa($this->registro_id);

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->org_empresa_id = $this->registro_id;

        $this->titulo_lista = 'Empresas';

        $link_org_sucursal_alta_bd = $obj_link->link_org_sucursal_alta_bd(org_empresa_id: $this->registro_id);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar link sucursal alta',
                data:  $link_org_sucursal_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_org_sucursal_alta_bd = $link_org_sucursal_alta_bd;

        $this->seccion_titulo = 'EMPRESAS';

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

    /**
     * @throws JsonException
     */
    public function alta_sucursal_bd(bool $header, bool $ws = false){

        $this->link->beginTransaction();


        $registro = $_POST;
        $registro['org_empresa_id'] = $this->registro_id;

        $r_alta_sucursal_bd = (new org_sucursal($this->link))->alta_registro(registro:$registro); // TODO: Change the autogenerated stub
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta sucursal',data:  $r_alta_sucursal_bd,
                header: $header,ws:$ws);
        }


        $this->link->commit();

        $siguiente_view = (new actions())->init_alta_bd();
        if(errores::$error){
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header:  $header, ws: $ws);
        }

        if($header){

            $retorno = (new actions())->retorno_alta_bd(registro_id:$this->registro_id,seccion: $this->tabla,
                siguiente_view: $siguiente_view);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al dar de alta registro', data: $r_alta_sucursal_bd,
                    header:  true, ws: $ws);
            }
            header('Location:'.$retorno);
            exit;
        }
        if($ws){
            header('Content-Type: application/json');
            echo json_encode($r_alta_sucursal_bd, JSON_THROW_ON_ERROR);
            exit;
        }

        return $r_alta_sucursal_bd;

    }

    /**
     * SIN PROBAR
     * @param string $key_general Key enviado a asignar
     * @param array $registro Registro de tipo org_empresa en forma alta
     * @return array
     */
    private function asigna_key_post(string $key_general, array $registro): array
    {
        if(isset($_POST[$key_general])){
            $registro[$key_general] = $_POST[$key_general];
        }
        return $registro;
    }

    /**
     * SIN PROBAR
     * @param array $keys_generales Keys a reasignar si existen en POST
     * @return array
     */
    private function asigna_keys_post(array $keys_generales): array
    {
        $registro = array();
        foreach ($keys_generales as $key_general){
            $registro = $this->asigna_key_post(key_general: $key_general,registro:  $registro);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al asignar key post',data:  $registro);
            }
        }
        return $registro;
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

    private function data_sucursal_btn(array $sucursal): array
    {

        $params['org_sucursal_id'] = $sucursal['org_sucursal_id'];

        $btn_elimina = $this->html_base->button_href(accion:'elimina_bd',etiqueta:  'Elimina',
            registro_id:  $sucursal['org_sucursal_id'], seccion: 'org_sucursal',style:  'danger');

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar btn',data:  $btn_elimina);
        }
        $sucursal['link_elimina'] = $btn_elimina;

        $btn_modifica = $this->html_base->button_href(accion:'modifica_sucursal',etiqueta:  'Modifica',
            registro_id:  $sucursal['org_empresa_id'], seccion: 'org_empresa',style:  'warning');

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar btn',data:  $btn_elimina);
        }
        $sucursal['link_modifica'] = $btn_modifica;

        $btn_ve = $this->html_base->button_href(accion:'ve_sucursal',etiqueta:  'Ver',
            registro_id:  $sucursal['org_empresa_id'], seccion: 'org_empresa',style:  'info', params: $params);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar btn',data:  $btn_elimina);
        }
        $sucursal['link_ve'] = $btn_ve;
        return $sucursal;
    }

    private function htmls_sucursal(): stdClass
    {
        $org_sucursal_html = (new org_sucursal_html(html: $this->html_base));
        $org_tipo_sucursal_html = (new org_tipo_sucursal_html(html: $this->html_base));
        $dp_estado_html = (new dp_estado_html(html: $this->html_base));
        $dp_municipio_html = (new dp_municipio_html(html: $this->html_base));
        $dp_colonia_html = (new dp_colonia_html(html: $this->html_base));
        $dp_cp_html = (new dp_cp_html(html: $this->html_base));
        $dp_calle_html = (new dp_calle_html(html: $this->html_base));

        $data = new stdClass();
        $data->org_sucursal = $org_sucursal_html;
        $data->org_tipo_sucursal = $org_tipo_sucursal_html;
        $data->dp_estado = $dp_estado_html;
        $data->dp_municipio = $dp_municipio_html;
        $data->dp_colonia = $dp_colonia_html;
        $data->dp_cp = $dp_cp_html;
        $data->dp_calle = $dp_calle_html;
        return $data;
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

    private function inputs_direcciones_by_sucursal(stdClass $data_dp, stdClass $htmls): array|stdClass
    {
        $dp_estado_descripcion = $htmls->dp_estado->input_descripcion(cols: 3,row_upd:  $data_dp->estado,
            value_vacio: false, disabled: true, place_holder: 'Estado');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener descripcion',data:  $dp_estado_descripcion);
        }

        $this->inputs->org_sucursal_dp_estado_descripcion = $dp_estado_descripcion;


        $dp_municipio_descripcion = $htmls->dp_municipio->input_descripcion(cols: 3,row_upd:  $data_dp->municipio,
            value_vacio: false, disabled: true, place_holder: 'Municipio');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener descripcion',data:  $dp_municipio_descripcion);
        }

        $this->inputs->org_sucursal_dp_municipio_descripcion = $dp_municipio_descripcion;


        $dp_colonia_descripcion = $htmls->dp_colonia->input_descripcion(cols: 3,row_upd:  $data_dp->colonia,
            value_vacio: false, disabled: true, place_holder: 'Colonia');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener $dp_colonia_descripcion',
                data:  $dp_colonia_descripcion);
        }

        $this->inputs->org_sucursal_dp_colonia_descripcion = $dp_colonia_descripcion;



        $dp_cp_descripcion = $htmls->dp_cp->input_descripcion(cols: 3,row_upd:  $data_dp->cp,
            value_vacio: false, disabled: true, place_holder: 'CP');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener $dp_cp_descripcion',
                data:  $dp_cp_descripcion);
        }

        $this->inputs->org_sucursal_dp_cp_descripcion = $dp_cp_descripcion;


        $dp_calle_descripcion = $htmls->dp_calle->input_descripcion(cols: 3,row_upd:  $data_dp->calle,
            value_vacio: false, disabled: true, place_holder: 'Calle');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener $dp_calle_descripcion',
                data:  $dp_calle_descripcion);
        }

        $this->inputs->org_sucursal_dp_calle_descripcion = $dp_calle_descripcion;

        return $this->inputs;
    }

    private function inputs_sucursal(org_sucursal_html $html, stdClass $org_sucursal): array|stdClass
    {


        $org_sucursal_codigo = $html->input_codigo(cols: 4,row_upd:  $org_sucursal, value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener sucursal_codigo select',data:  $org_sucursal_codigo);
        }

        $org_sucursal_codigo_bis = $html->input_codigo_bis(cols: 4,row_upd:  $org_sucursal, value_vacio: false,
            disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener sucursal_codigo_bis',
                data:  $org_sucursal_codigo_bis);
        }


        $org_sucursal_descripcion = $html->input_descripcion(cols: 12,row_upd:  $org_sucursal, value_vacio: false,
            disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener descripcion',data:  $org_sucursal_descripcion);
        }


        $org_sucursal_fecha_inicio_operaciones = $html->fec_fecha_inicio_operaciones(cols: 6, row_upd:  $org_sucursal,
            value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener fecha_inicio_operaciones',data:  $org_sucursal_fecha_inicio_operaciones);
        }

        $org_sucursal_exterior = $html->input_exterior(cols: 6, row_upd:  $org_sucursal,
            value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener $org_sucursal_exterior',data:  $org_sucursal_exterior);
        }

        $org_sucursal_id = $html->input_id(cols: 4,row_upd:  $org_sucursal, value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener sucursal_id select',data:  $org_sucursal_id);
        }

        $org_sucursal_interior = $html->input_interior(cols: 6, row_upd:  $org_sucursal,
            value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener $org_sucursal_interior',data:  $org_sucursal_interior);
        }


        $org_sucursal_serie = $html->input_serie(cols: 6,row_upd:  $org_sucursal, value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener serie',data:  $org_sucursal_serie);
        }

        $org_sucursal_telefono_1 = $html->telefono_1(cols: 6,row_upd:  $org_sucursal, value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener serie',data:  $org_sucursal_telefono_1);
        }
        $org_sucursal_telefono_2 = $html->telefono_2(cols: 6,row_upd:  $org_sucursal, value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener serie',data:  $org_sucursal_telefono_1);
        }
        $org_sucursal_telefono_3 = $html->telefono_3(cols: 6,row_upd:  $org_sucursal, value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener serie',data:  $org_sucursal_telefono_1);
        }


        $this->inputs->org_sucursal_codigo = $org_sucursal_codigo;
        $this->inputs->org_sucursal_codigo_bis = $org_sucursal_codigo_bis;
        $this->inputs->org_sucursal_descripcion = $org_sucursal_descripcion;
        $this->inputs->org_sucursal_exterior = $org_sucursal_exterior;
        $this->inputs->org_sucursal_fecha_inicio_operaciones = $org_sucursal_fecha_inicio_operaciones;
        $this->inputs->org_sucursal_id = $org_sucursal_id;
        $this->inputs->org_sucursal_interior = $org_sucursal_interior;
        $this->inputs->org_sucursal_serie = $org_sucursal_serie;
        $this->inputs->org_sucursal_telefono_1 = $org_sucursal_telefono_1;
        $this->inputs->org_sucursal_telefono_2 = $org_sucursal_telefono_2;
        $this->inputs->org_sucursal_telefono_3 = $org_sucursal_telefono_3;

        return $this->inputs;
    }

    public function lista(bool $header, bool $ws = false): array
    {
        $r_lista = parent::lista($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $r_lista, header: $header,ws:$ws);
        }

        foreach ($this->registros as $indice=> $row){
            $link_sucursales = $this->obj_link->link_con_id(accion:'sucursales',registro_id:  $row->org_empresa_id,
                seccion:  $this->tabla);
            $row->link_sucursales = $link_sucursales;
            $row->link_sucursales_style = 'info';

            $this->registros[$indice] = $row;

        }


        return $r_lista;
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
     * SIN PROBAR
     * @author israel hernandez 0.1.0
     * @version v0.88.23
     * @version v0.1.0
     * @version v0.2.0
     * @created 2022-08-01
     * @throws JsonException
     */
    public function modifica_cif(bool $header, bool $ws = false): array|stdClass
    {
        $keys_cifs[] = 'cat_sat_regimen_fiscal_id';
        $keys_cifs[] = 'fecha_inicio_operaciones';
        $keys_cifs[] = 'fecha_ultimo_cambio_sat';
        $keys_cifs[] = 'email_sat';

        $r_modifica_bd = $this->upd_base(keys_generales: $keys_cifs);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al modificar cif',data:  $r_modifica_bd,
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


        $r_modifica_bd = $this->upd_base(keys_generales: $keys_generales);
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

    private function select_org_empresa_id(): array|string
    {
        $select = (new org_empresa_html(html: $this->html_base))->select_org_empresa_id(cols:12,con_registros: true,
            id_selected: $this->registro_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar select datos',data:  $select);
        }
        $this->inputs->select->org_empresa_id = $select;

        return $select;
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

        $select = $this->select_org_empresa_id();

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar select datos',data:  $select,
                header: $header,ws:$ws);
        }


        $sucursales = (new org_sucursal($this->link))->sucursales(org_empresa_id: $this->org_empresa_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener sucursales',data:  $sucursales, header: $header,ws:$ws);
        }

        foreach ($sucursales->registros as $indice=>$sucursal){

            $sucursal = $this->data_sucursal_btn(sucursal:$sucursal);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error al asignar botones',data:  $sucursal, header: $header,ws:$ws);
            }
            $sucursales->registros[$indice] = $sucursal;

        }

        $this->sucursales = $sucursales;

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

    /**
     * SIN PROBAR
     * @param array $keys_generales Keys a reasignar si existen en POST
     * @return array|stdClass
     * @throws JsonException
     */
    private function upd_base(array $keys_generales): array|stdClass
    {
        $registro = $this->asigna_keys_post(keys_generales: $keys_generales);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al asignar keys post',data:  $registro);
        }

        $r_modifica_bd = $this->modelo->modifica_bd(registro: $registro, id: $this->registro_id);
        if(errores::$error){

            return $this->errores->error(mensaje: 'Error al modificar generales',data:  $r_modifica_bd);
        }
        return $r_modifica_bd;
    }

    public function ve_sucursal(bool $header, bool $ws = false): array|stdClass
    {

        $params = new stdClass();

        $params->codigo= new stdClass();
        $params->codigo->disabled = true;

        $params->codigo_bis = new stdClass();
        $params->codigo_bis->cols = 6;
        $params->codigo_bis->disabled = true;

        $params->razon_social = new stdClass();
        $params->razon_social->disabled = true;


        $base = $this->base(params: $params);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }
        $select = $this->select_org_empresa_id();

        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar select datos',data:  $select,
                header: $header,ws:$ws);
        }

        $data_sucursal = (new org_sucursal($this->link))->data_sucursal_obj(org_sucursal_id: $_GET['org_sucursal_id']);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener sucursal',data:  $data_sucursal,
                header: $header,ws:$ws);
        }


        $data_dp = (new dp_calle_pertenece($this->link))->objs_direcciones(
            dp_calle_pertenece_id: $data_sucursal->org_sucursal->dp_calle_pertenece_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener datos de direcciones',data:  $data_dp,
                header: $header,ws:$ws);
        }

        $htmls = $this->htmls_sucursal();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener htmls',data:  $htmls, header: $header,ws:$ws);
        }

        $inputs_sucursal = $this->inputs_sucursal(html:$htmls->org_sucursal, org_sucursal: $data_sucursal->org_sucursal);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar inputs sucursal',
                data:  $inputs_sucursal, header: $header,ws:$ws);
        }

        $org_tipo_sucursal_descripcion = $htmls->org_tipo_sucursal->input_descripcion(cols: 12,
            row_upd:  $data_sucursal->org_tipo_sucursal, value_vacio: false, disabled: true);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener descripcion',data:  $org_tipo_sucursal_descripcion,
                header: $header,ws:$ws);
        }

        $this->inputs->org_sucursal_tipo_sucursal_descricpion = $org_tipo_sucursal_descripcion;

        $inputs_dp = $this->inputs_direcciones_by_sucursal(data_dp: $data_dp,htmls:  $htmls);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar inputs de direcciones',
                data:  $inputs_dp, header: $header,ws:$ws);
        }

        return $base;
    }

}
