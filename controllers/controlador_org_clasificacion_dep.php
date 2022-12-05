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
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\actions;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\org_clasificacion_dep_html;
use PDO;
use stdClass;

class controlador_org_clasificacion_dep extends _ctl_parent_sin_codigo {

    public array $keys_selects = array();
    public controlador_org_departamento $controlador_org_departamento;

    public int $org_departamento_id = -1;
    public string $link_org_departamento_alta_bd = '';
    public string $link_org_departamento_modifica_bd = '';

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_clasificacion_dep(link: $link);
        $html_ = new org_clasificacion_dep_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['org_clasificacion_dep_id']['titulo'] = 'Id';
        $datatables->columns['org_clasificacion_dep_descripcion']['titulo'] = 'Clasificacion Depto';
        //$datatables->columns['adm_menu_n_secciones']['titulo'] = 'Secciones';

        $datatables->filtro = array();
        $datatables->filtro[] = 'org_clasificacion_dep.id';
        $datatables->filtro[] = 'org_clasificacion_dep.descripcion';

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Clasificacion de departamentos';

        $this->controlador_org_departamento= new controlador_org_departamento(link:$this->link, paths_conf: $paths_conf);



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

    public function asignar_propiedad(string $identificador, mixed $propiedades)
    {
        if (!array_key_exists($identificador,$this->keys_selects)){
            $this->keys_selects[$identificador] = new stdClass();
        }

        foreach ($propiedades as $key => $value){
            $this->keys_selects[$identificador]->$key = $value;
        }
    }



    private function inicializa_priedades(): array
    {
        $identificador = "codigo";
        $propiedades = array("place_holder" => "Codigo");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "codigo_bis";
        $propiedades = array("place_holder" => "Codigo BIS");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        return $this->keys_selects;
    }

    public function departamentos(bool $header, bool $ws = false): array|stdClass
    {
        $columns["org_departamento_id"]["titulo"] = "Id";
        $columns["org_departamento_codigo"]["titulo"] = "Codigo";
        $columns["org_departamento_descripcion"]["titulo"] = "Descripcion";
        $columns["org_clasificacion_dep_descripcion"]["titulo"] = "Clasificacion";
        $columns["org_empresa_descripcion"]["titulo"] = "Empresa";
        $columns["modifica"]["titulo"] = "Acciones";
        $columns["modifica"]["type"] = "button";
        $columns["modifica"]["campos"] = array("elimina_bd");

        $colums_rs =$this->datatable_init(columns: $columns,identificador: "#org_departamento",
            data: array("org_departamento.org_clasificacion_dep_id" => $this->registro_id));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $colums_rs);
            print_r($error);
            die('Error');
        }

        $alta = $this->controlador_org_departamento->alta(header: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $alta, header: $header, ws: $ws);
        }

        $this->controlador_org_departamento->asignar_propiedad(identificador: 'org_clasificacion_dep_id',
            propiedades: ["id_selected" => $this->registro_id, "disabled" => true,
                "filtro" => array('org_clasificacion_dep.id' => $this->registro_id)]);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al asignar propiedad', data: $this, header: $header, ws: $ws);
        }

        $this->inputs = $this->controlador_org_departamento->genera_inputs(
            keys_selects:  $this->controlador_org_departamento->keys_selects);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $this->inputs);
            print_r($error);
            die('Error');
        }

        return $this->inputs;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Clas Depto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }



    // ---- POR REVISAR ----

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
}
