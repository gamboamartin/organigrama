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
use gamboamartin\organigrama\models\org_departamento;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template\html;
use html\org_departamento_html;
use PDO;
use stdClass;

class controlador_org_departamento extends system {

    public array $keys_selects = array();

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_departamento(link: $link);
        $html_ = new org_departamento_html($html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $columns["org_departamento_id"]["titulo"] = "Id";
        $columns["org_departamento_codigo"]["titulo"] = "Codigo";
        $columns["org_departamento_descripcion"]["titulo"] = "Descripcion";
        $columns["org_clasificacion_dep_descripcion"]["titulo"] = "Clasificacion";
        $columns["org_empresa_descripcion"]["titulo"] = "Empresa";

        $filtro = array("org_departamento.id","org_departamento.codigo","org_departamento.descripcion",
            "org_clasificacion_dep.descripcion", "rg_empresa.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Departamentos';

        $propiedades = $this->inicializa_priedades();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar propiedades',data:  $propiedades);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false, ws: false);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->row_upd->fecha = date('Y-m-d');
        $this->row_upd->subtotal = 0;
        $this->row_upd->descuento = 0;
        $this->row_upd->impuestos_trasladados = 0;
        $this->row_upd->impuestos_retenidos = 0;
        $this->row_upd->total = 0;

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

        $this->asignar_propiedad(identificador:'org_empresa_id',
            propiedades: ["id_selected"=>$this->row_upd->org_empresa_id]);
        $this->asignar_propiedad(identificador:'org_clasificacion_dep_id',
            propiedades: ["id_selected"=>$this->row_upd->org_clasificacion_dep_id]);

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
        $identificador = "org_empresa_id";
        $propiedades = array("label" => "Empresa", "cols" => 12);
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "org_clasificacion_dep_id";
        $propiedades = array("label" => "Clasificacion");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        $identificador = "codigo";
        $propiedades = array("place_holder" => "Codigo");
        $this->asignar_propiedad(identificador:$identificador, propiedades: $propiedades);

        return $this->keys_selects;
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
}
