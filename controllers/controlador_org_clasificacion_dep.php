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
use gamboamartin\organigrama\models\org_clasificacion_dep;
use gamboamartin\organigrama\models\org_departamento;
use gamboamartin\system\actions;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template\html;
use html\org_clasificacion_dep_html;
use PDO;
use stdClass;

class controlador_org_clasificacion_dep extends system {
    public stdClass $departamentos ;
    public int $org_departamento_id = -1;
    public controlador_org_departamento $controlador_org_departamento;
    public string $link_org_departamento_alta_bd = '';
    public string $link_org_departamento_modifica_bd = '';
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new org_clasificacion_dep(link: $link);
        $html_ = new org_clasificacion_dep_html($html);
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Clasificacion de departamentos';

        if (isset($_GET['org_departamento_id'])){
            $this->org_departamento_id = $_GET['org_departamento_id'];
        }

        $this->controlador_org_departamento= new controlador_org_departamento($this->link);

        $link_org_departamento_modifica_bd = $obj_link->link_con_id(accion:'modifica_departamento_bd',
            registro_id: $this->registro_id,seccion:  'org_clasificacion_dep');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar link', data: $link_org_departamento_modifica_bd);
            print_r($error);
            die('Error');
        }
        $link_org_departamento_modifica_bd .= '&org_departamento_id='.$this->registro_id;

        $this->link_org_departamento_modifica_bd = $link_org_departamento_modifica_bd;

        $link_org_departamento_alta_bd = $obj_link->link_con_id(accion:'alta_departamento_bd',
            registro_id: $this->registro_id,seccion:  'org_clasificacion_dep');
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar link', data: $link_org_departamento_alta_bd);
            print_r($error);
            die('Error');
        }
        $this->link_org_departamento_alta_bd = $link_org_departamento_alta_bd;

    }

    public function alta_departamento_bd(bool $header, bool $ws = false): array|stdClass
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
        $_POST['org_clasificacion_dep_id'] = $this->registro_id;

        $alta = (new org_departamento($this->link))->alta_registro(registro: $_POST);
        if (errores::$error) {
            $this->link->rollBack();
            return $this->retorno_error(mensaje: 'Error al dar de alta departamento', data: $alta,
                header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $alta,
                siguiente_view: "departamentos", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($alta, JSON_THROW_ON_ERROR);
            exit;
        }
        $alta->siguiente_view = "departamentos";

        return $alta;
    }

    private function data_departamento_btn(array $departamento): array
    {

        $params['org_departamento_id'] = $departamento['org_departamento_id'];

        $btn_elimina = $this->html_base->button_href(accion:'elimina_bd',etiqueta:  'Elimina',
            registro_id:  $departamento['org_departamento_id'], seccion: 'org_departamento',style:  'danger');

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar btn',data:  $btn_elimina);
        }
        $departamento['link_elimina'] = $btn_elimina;


        $btn_modifica = $this->html_base->button_href(accion:'modifica_departamento',etiqueta:  'Modifica',
            registro_id:  $departamento['org_clasificacion_dep_id'], seccion: 'org_clasificacion_dep',style:  'warning',
            params: $params);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar btn',data:  $btn_elimina);
        }
        $departamento['link_modifica'] = $btn_modifica;

        return $departamento;
    }

    public function departamentos(bool $header, bool $ws = false): array|stdClass
    {
        $alta = $this->controlador_org_departamento->alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $alta, header: $header, ws: $ws);
        }

        $this->controlador_org_departamento->asignar_propiedad(identificador: 'org_clasificacion_dep_id',
            propiedades: ["id_selected" => $this->registro_id, "disabled" => true,
                "filtro" => array('org_clasificacion_dep.id' => $this->registro_id), 'label' =>' Clasificacion Dep.']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al asignar propiedad', data: $this, header: $header, ws: $ws);
        }

        $this->controlador_org_departamento->asignar_propiedad(identificador:'org_empresa_id',
            propiedades: ["label" => "Empresa"]);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al asignar propiedad', data: $this, header: $header, ws: $ws);
        }

        $this->controlador_org_departamento->keys_selects['descripcion'] = new stdClass();
        $this->controlador_org_departamento->keys_selects['descripcion']->cols = 6;
        $this->controlador_org_departamento->keys_selects['descripcion']->place_holder = 'Descripcion';
        $this->inputs = $this->controlador_org_departamento->genera_inputs(
            keys_selects:  $this->controlador_org_departamento->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $this->inputs);
            print_r($error);
            die('Error');
        }

        $this->departamentos = $this->ver_departamentos(header: $header,ws: $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener los anticipos',data:  $this->departamentos, header: $header,ws:$ws);
        }

        return $this->inputs;
    }

    public function modifica_departamento(bool $header, bool $ws = false): array|stdClass
    {
        $this->controlador_org_departamento->registro_id = $this->org_departamento_id;

        $modifica = $this->controlador_org_departamento->modifica(header: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $modifica, header: $header,ws:$ws);
        }

        $this->controlador_org_departamento->keys_selects['org_clasificacion_dep_id']->disabled = true;
        $this->controlador_org_departamento->keys_selects['descripcion'] = new stdClass();
        $this->controlador_org_departamento->keys_selects['descripcion']->cols = 12;
        $this->controlador_org_departamento->keys_selects['descripcion']->place_holder = 'Descripcion';
        $this->inputs = $this->controlador_org_departamento->genera_inputs(
            keys_selects:  $this->controlador_org_departamento->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $this->inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    public function modifica_departamento_bd(bool $header, bool $ws = false): array|stdClass
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

        $r_modifica = (new org_departamento($this->link))->modifica_bd(registro: $registros,
            id: $this->org_departamento_id);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al modificar anticipo', data: $r_modifica, header: $header, ws: $ws);
        }

        $this->link->commit();

        if ($header) {
            $this->retorno_base(registro_id:$this->registro_id, result: $r_modifica,
                siguiente_view: "departamentos", ws:  $ws);
        }
        if ($ws) {
            header('Content-Type: application/json');
            echo json_encode($r_modifica, JSON_THROW_ON_ERROR);
            exit;
        }
        $r_modifica->siguiente_view = "departamentos";

        return $r_modifica;
    }

    public function ver_departamentos(bool $header, bool $ws = false): array|stdClass
    {
        $departamentos = (new org_departamento($this->link))->departamentos_por_cls(org_clasificacion_dep_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener departamentos',data:  $departamentos,
                header: $header,ws:$ws);
        }

        foreach ($departamentos->registros as $indice => $departamento) {

            $departamento = $this->data_departamento_btn(departamento: $departamento);
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'Error al asignar botones', data: $departamento, header: $header, ws: $ws);
            }

            $departamentos->registros[$indice] = $departamento;
        }

        $this->departamentos = $departamentos;

        return $this->departamentos;
    }
}
