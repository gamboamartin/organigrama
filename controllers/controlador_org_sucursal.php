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

    public controlador_im_registro_patronal $controlador_im_registro_patronal;

    public string $link_im_registro_patronal_alta_bd = '';
    public string $link_im_registro_patronal_modifica_bd = '';

    public int $im_registro_patronal_id = -1;
    protected int $org_empresa_id = -1;

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new org_sucursal(link: $link);
        $html_ = new org_sucursal_html($html);
        $obj_link = new link_org_sucursal($this->registro_id);

        $columns["org_sucursal_id"]["titulo"] = "Id";
        $columns["org_sucursal_codigo"]["titulo"] = "Cogido";
        $columns["org_sucursal_descripcion"]["titulo"] = "Descripcion";
        $columns["org_tipo_sucursal_descripcion"]["titulo"] = "Tipo Sucursal";

        $datatables = new stdClass();
        $datatables->columns = $columns;

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables, paths_conf: $paths_conf);

        $this->controlador_im_registro_patronal = new controlador_im_registro_patronal(
            link: $this->link, paths_conf: $paths_conf);

        $obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $obj_link);
            print_r($error);
            die('Error');
        }
        $this->link_im_registro_patronal_alta_bd = $obj_link->links->org_sucursal->registro_patronal_alta_bd;
        $this->link_im_registro_patronal_modifica_bd = $obj_link->links->org_sucursal->registro_patronal_modifica_bd;

        $this->titulo_lista = 'Sucursales';

        if (isset($_GET['im_registro_patronal_id'])){
            $this->im_registro_patronal_id = $_GET['im_registro_patronal_id'];
        }
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

    public function get_sucursal(bool $header, bool $ws = true): array|stdClass
    {
        $keys['org_empresa'] = array('id','descripcion','codigo','codigo_bis');;

        $salida = $this->get_out(header: $header,keys: $keys, ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar salida',data:  $salida,header: $header,ws: $ws);
        }

        return $salida;
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

        $columns2["im_registro_patronal_id"]["titulo"] = "Id";
        $columns2["im_registro_patronal_codigo"]["titulo"] = "Codigo";
        $columns2["im_registro_patronal_descripcion"]["titulo"] = "Descripcion";


        $this->datatable_init(columns: $columns2,identificador: "#im_registro_patronal");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $columns2);
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




}
