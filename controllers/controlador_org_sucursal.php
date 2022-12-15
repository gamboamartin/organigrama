<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use gamboamartin\empleado\models\em_empleado;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\controllers\controlador_fc_csd;
use gamboamartin\im_registro_patronal\controllers\controlador_im_registro_patronal;
use gamboamartin\organigrama\controllers\base\empresas;
use gamboamartin\organigrama\links\secciones\link_org_sucursal;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\system\actions;
use gamboamartin\template\html;
use html\org_sucursal_html;
use models\im_registro_patronal;
use PDO;
use stdClass;

class controlador_org_sucursal extends empresas {

    public array $keys_selects = array();
    public controlador_im_registro_patronal $controlador_im_registro_patronal;

    public string $link_dp_pais_alta = '';
    public string $link_dp_estado_alta = '';
    public string $link_dp_municipio_alta = '';
    public string $link_dp_cp_alta = '';
    public string $link_dp_colonia_postal_alta = '';
    public string $link_dp_calle_pertenece_alta = '';
    public string $link_org_empresa_alta = '';
    public string $link_im_registro_patronal_alta_bd = '';
    public string $link_im_registro_patronal_modifica_bd = '';

    public int $im_registro_patronal_id = -1;
    protected int $org_empresa_id = -1;

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new org_sucursal(link: $link);
        $html_ = new org_sucursal_html($html);
        $obj_link = new link_org_sucursal(link: $link, registro_id: $this->registro_id);

        $columns["org_sucursal_id"]["titulo"] = "Id";
        $columns["org_sucursal_codigo"]["titulo"] = "Cogido";
        $columns["org_sucursal_descripcion"]["titulo"] = "Descripcion";
        $columns["org_tipo_sucursal_descripcion"]["titulo"] = "Tipo Sucursal";

        $datatables = new stdClass();
        $datatables->columns = $columns;

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->controlador_im_registro_patronal = new controlador_im_registro_patronal(link: $this->link,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Sucursales';

        /*$links = $this->inicializa_links();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar links',data:  $links);
            print_r($error);
            die('Error');
        }*/

        $propiedades = $this->inicializa_propiedades();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
            print_r($error);
            die('Error');
        }

        $ids = $this->inicializa_ids();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar ids',data:  $ids);
            print_r($error);
            die('Error');
        }
    }
    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->row_upd->fecha_inicio_operaciones = date('Y-m-d');

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_alta;
    }

    public function asignar_propiedad(string $identificador, mixed $propiedades)
    {
        if (!array_key_exists($identificador,$this->keys_selects)){
            $this->keys_selects[$identificador] = new stdClass();
        }

        foreach ($propiedades as $key => $value){
            $this->keys_selects[$identificador]->$key = $value;
        }
    }

    private function base(): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false,aplica_form:  false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_modifica);
        }

        $direccion = (new em_empleado($this->link))->get_direccion(
            dp_calle_pertenece_id: $this->row_upd->dp_calle_pertenece_id);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener direccion',data:  $direccion);
        }

        $this->asignar_propiedad(identificador:'dp_pais_id',
            propiedades: ["id_selected"=> $direccion["dp_pais_id"]]);
        $this->asignar_propiedad(identificador:'dp_estado_id',
            propiedades: ["id_selected"=> $direccion["dp_estado_id"],"con_registros"=>true,
                "filtro" => array('dp_estado.id' => $direccion["dp_estado_id"])]);
        $this->asignar_propiedad(identificador:'dp_municipio_id',
            propiedades: ["id_selected"=> $direccion["dp_municipio_id"],"con_registros"=>true,
                "filtro" => array('dp_municipio.id' => $direccion["dp_municipio_id"])]);
        $this->asignar_propiedad(identificador:'dp_cp_id',
            propiedades: ["id_selected"=> $direccion["dp_cp_id"],"con_registros"=>true,
                "filtro" => array('dp_cp.id' => $direccion["dp_cp_id"])]);
        $this->asignar_propiedad(identificador:'dp_colonia_postal_id',
            propiedades: ["id_selected"=> $direccion["dp_colonia_postal_id"],"con_registros"=>true,
                "filtro" => array('dp_colonia_postal.id' => $direccion["dp_colonia_postal_id"])]);
        $this->asignar_propiedad(identificador:'dp_calle_pertenece_id',
            propiedades: ["id_selected"=>$this->row_upd->dp_calle_pertenece_id,"con_registros"=>true,
                "filtro" => array('dp_calle_pertenece.id' => $this->row_upd->dp_calle_pertenece_id)]);
        $this->asignar_propiedad(identificador:'org_empresa_id', propiedades:
            ["id_selected" => $this->row_upd->org_empresa_id]);
        $this->asignar_propiedad(identificador:'org_tipo_sucursal_id', propiedades:
            ["id_selected" => $this->row_upd->org_tipo_sucursal_id]);

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $inputs);
        }

        $data = new stdClass();
        $data->template = $r_modifica;
        $data->inputs = $inputs;

        return $data;
    }

    public function csd(bool $header, bool $ws = false): array|stdClass
    {
        $controlador_fc_csd = new controlador_fc_csd($this->link);

        $alta = $controlador_fc_csd->alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $alta, header: $header, ws: $ws);
        }
        $this->inputs = $controlador_fc_csd->inputs;
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $this->inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function get_sucursal(bool $header, bool $ws = true): array|stdClass
    {
        $keys['org_empresa'] = array('id','descripcion','codigo','codigo_bis');;

        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);
        }

        return $salida;
    }

    private function inicializa_links(): array|string
    {
        $this->obj_link->genera_links($this);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar links para sucursal',data:  $this->obj_link);
        }


        $link = $this->obj_link->get_link('dp_pais',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_pais_alta',data:  $link);
        }
        $this->link_dp_pais_alta = $link;

        $link = $this->obj_link->get_link('dp_estado',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_estado_alta',data:  $link);
        }
        $this->link_dp_estado_alta = $link;

        $link = $this->obj_link->get_link('dp_municipio',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_municipio_alta',data:  $link);
        }
        $this->link_dp_municipio_alta = $link;

        $link = $this->obj_link->get_link('dp_cp',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_cp_alta',data:  $link);
        }
        $this->link_dp_cp_alta = $link;

        $link = $this->obj_link->get_link('dp_colonia_postal',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_colonia_postal_alta',data:  $link);
        }
        $this->link_dp_colonia_postal_alta = $link;

        $link = $this->obj_link->get_link('dp_calle_pertenece',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link dp_calle_pertenece_alta',data:  $link);
        }
        $this->link_dp_calle_pertenece_alta = $link;

        $link = $this->obj_link->get_link('org_empresa',"alta");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link partida registro_patronal_alta_bd',data:  $link);
        }
        $this->link_org_empresa_alta = $link;

        $link = $this->obj_link->get_link($this->seccion,"registro_patronal_alta_bd");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link partida registro_patronal_alta_bd',data:  $link);
        }
        $this->link_im_registro_patronal_alta_bd = $link;

        $link = $this->obj_link->get_link($this->seccion,"registro_patronal_modifica_bd");
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener link partida conf_percepcion_alta_bd',data:  $link);
        }
        $this->link_im_registro_patronal_modifica_bd = $link;

        return $link;
    }

    private function inicializa_propiedades(): array
    {
        $identificador = "org_empresa_id";
        $propiedades = array("label" => "Empresa","cols" => 12, "extra_params_keys" =>
            array("org_empresa_fecha_inicio_operaciones","dp_pais_id","dp_estado_id","dp_municipio_id","dp_cp_id",
                "dp_colonia_postal_id","dp_calle_pertenece_id"));
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "org_tipo_sucursal_id";
        $propiedades = array("label" => "Tipo Sucursal","cols" => 12);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "dp_pais_id";
        $propiedades = array("label" => "Pais");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "dp_estado_id";
        $propiedades = array("label" => "Estado","con_registros"=> false);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "dp_municipio_id";
        $propiedades = array("label" => "Municipio","con_registros"=> false);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "dp_cp_id";
        $propiedades = array("label" => "CP","con_registros"=> false);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "dp_colonia_postal_id";
        $propiedades = array("label" => "Colonia Postal","con_registros"=> false);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "dp_calle_pertenece_id";
        $propiedades = array("label" => "Calle","con_registros"=> false);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "id";
        $propiedades = array("place_holder" => "Id","disabled" => true);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "codigo";
        $propiedades = array("place_holder" => "Codigo");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "serie";
        $propiedades = array("place_holder" => "Serie");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "exterior";
        $propiedades = array("place_holder" => "Exterior");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "interior";
        $propiedades = array("place_holder" => "Interior");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "telefono_1";
        $propiedades = array("place_holder" => "telefono 1","cols" => 4);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "telefono_2";
        $propiedades = array("place_holder" => "Telefono 2","cols" => 4);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "telefono_3";
        $propiedades = array("place_holder" => "Telefono 3","cols" => 4);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "fecha_inicio_operaciones";
        $propiedades = array("place_holder" => "Fecha Inicio");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        return $this->keys_selects;
    }

    private function inicializa_ids(): array
    {
        if (isset($_GET['im_registro_patronal_id'])){
            $this->im_registro_patronal_id = $_GET['im_registro_patronal_id'];
        }

        return $_GET;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base->template;
    }

    public function registro_patronal(bool $header, bool $ws = false): array|stdClass
    {
        $this->controlador_im_registro_patronal->modelo->campos_view['org_sucursal_id'] = array('type' => 'selects',
            'model' => new org_sucursal($this->link));

        $this->controlador_im_registro_patronal->asignar_propiedad(identificador: 'org_sucursal_id',
            propiedades: ["id_selected" => $this->registro_id, "disabled" => true, "cols" => 12, "label" => "Sucursal",
                "filtro" => array('org_sucursal.id' => $this->registro_id)]);

        $this->controlador_im_registro_patronal->asignar_propiedad(identificador: 'descripcion',
            propiedades: ['place_holder' => "Descripcion",'cols'=> 12]);

        $columns["im_registro_patronal_id"]["titulo"] = "Id";
        $columns["im_registro_patronal_codigo"]["titulo"] = "Codigo";
        $columns["im_registro_patronal_descripcion"]["titulo"] = "Descripcion";

        $colums_rs =$this->datatable_init(columns: $columns,identificador: "#im_registro_patronal",
            data: array("org_sucursal.id" => $this->registro_id));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $colums_rs);
            print_r($error);
            die('Error');
        }

        $alta = $this->controlador_im_registro_patronal->alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $alta, header: $header, ws: $ws);
        }

        $this->inputs = $this->controlador_im_registro_patronal->genera_inputs(
            keys_selects:  $this->controlador_im_registro_patronal->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $this->inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function registro_patronal_alta_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $alta = (new im_registro_patronal($this->link))->alta_registro(registro: $_POST);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta registro patronal', data: $alta,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $alta,
                siguiente_view: "registro_patronal", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($alta, JSON_THROW_ON_ERROR);
            exit;
        }
        $alta->siguiente_view = "registro_patronal";

        return $alta;
    }

    public function registro_patronal_modifica(bool $header, bool $ws = false): array|stdClass
    {
        $this->controlador_im_registro_patronal->registro_id = $this->im_registro_patronal_id;

        $modifica = $this->controlador_im_registro_patronal->modifica(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $modifica, header: $header,ws:$ws);
        }

        $this->controlador_im_registro_patronal->modelo->campos_view['org_sucursal_id'] = array('type' => 'selects',
            'model' => new org_sucursal($this->link));

        $this->controlador_im_registro_patronal->asignar_propiedad(identificador: 'org_sucursal_id',
            propiedades: ["id_selected" => $this->registro_id, "disabled" => true, "cols" => 12, "label" => "Sucursal",
                "filtro" => array('org_sucursal.id' => $this->registro_id)]);

        $this->controlador_im_registro_patronal->asignar_propiedad(identificador: 'im_clase_riesgo_id',
            propiedades: ["cols" => 6]);


        $this->inputs = $this->controlador_im_registro_patronal->genera_inputs(
            keys_selects:  $this->controlador_im_registro_patronal->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $this->inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function registro_patronal_modifica_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $registros = $_POST;

        $r_modifica = (new im_registro_patronal($this->link))->modifica_bd(registro: $registros,
            id: $this->im_registro_patronal_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al modificar registro patronal', data: $r_modifica, header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $r_modifica,
                siguiente_view: "registro_patronal", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_modifica, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_modifica->siguiente_view = "registro_patronal";

        return $r_modifica;
    }

    public function registro_patronal_elimina_bd(bool $header, bool $ws = false): array|stdClass
    {
        $this->link->beginTransaction();

        $siguiente_view = (new actions())->init_alta_bd();
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al obtener siguiente view', data: $siguiente_view,
                header: $header, ws: $ws);
        }

        if (isset($_POST['btn_action_next'])) {
            unset($_POST['btn_action_next']);
        }

        $r_elimina = (new im_registro_patronal($this->link))->elimina_bd(id: $this->im_registro_patronal_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al eliminar otro pago', data: $r_elimina, header: $header,
                ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $r_elimina,
                siguiente_view: "registro_patronal", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_elimina, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_elimina->siguiente_view = "registro_patronal";

        return $r_elimina;
    }
}
