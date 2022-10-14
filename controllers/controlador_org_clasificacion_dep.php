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
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template\html;
use html\org_clasificacion_dep_html;
use PDO;
use stdClass;

class controlador_org_clasificacion_dep extends system {
    public stdClass $departamentos ;
    public controlador_org_departamento $controlador_org_departamento;
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){

        $modelo = new org_clasificacion_dep(link: $link);
        $html_ = new org_clasificacion_dep_html($html);
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Clasificacion de departamentos';
        $this->controlador_org_departamento= new controlador_org_departamento($this->link);

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
            registro_id:  $departamento['org_empresa_id'], seccion: 'org_empresa',style:  'warning', params: $params);

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
