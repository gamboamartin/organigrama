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

    public array $keys_selects = array();
    public controlador_org_departamento $controlador_org_departamento;

    public stdClass $departamentos ;
    public int $org_departamento_id = -1;

    public string $link_org_departamento_alta_bd = '';
    public string $link_org_departamento_modifica_bd = '';


    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_clasificacion_dep(link: $link);
        $html_ = new org_clasificacion_dep_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Clasificacion de departamentos';

        $this->controlador_org_departamento= new controlador_org_departamento(link:$this->link, paths_conf: $paths_conf);

        $propiedades = $this->inicializa_priedades();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
            print_r($error);
            die('Error');
        }
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

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $inputs);
        }

        $data = new stdClass();
        $data->template = $r_modifica;
        $data->inputs = $inputs;

        return $data;
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
}
