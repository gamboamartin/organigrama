<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_puesto;
use gamboamartin\system\html_controler;
use models\org_puesto;

use PDO;
use stdClass;

class org_puesto_html extends html_controler {

    private function asigna_inputs(controlador_org_puesto $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();
        $controler->inputs->select->org_empresa_id = $inputs->selects->org_empresa_id;
        $controler->inputs->select->org_tipo_puesto_id = $inputs->selects->org_tipo_puesto_id;

        return $controler->inputs;
    }

    public function genera_inputs_alta(controlador_org_puesto $controler,PDO $link): array|stdClass
    {
        $inputs = $this->init_alta(link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);

        }
        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function genera_inputs_modifica(controlador_org_puesto $controler,PDO $link): array|stdClass
    {
        $inputs = $this->init_alta(link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);
        }

        $inputs_asignados = $this->asigna_inputs(controler:$controler, inputs: $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al asignar inputs',data:  $inputs_asignados);
        }

        return $inputs_asignados;
    }

    private function init_alta(PDO $link): array|stdClass
    {
        $selects = $this->selects_alta(link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->selects = $selects;
        return $alta_inputs;
    }

    private function selects_alta(PDO $link): array|stdClass
    {
        $selects = new stdClass();

        $select = (new org_tipo_puesto_html(html: $this->html_base))->select_org_tipo_puesto_id(cols: 12, con_registros:true,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }
        $selects->org_empresa_id = $select;

        $select = (new org_empresa_html(html: $this->html_base))->select_org_empresa_id(
            cols: 12, con_registros:true, id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }
        $selects->org_tipo_puesto_id = $select;

        return $selects;
    }

    public function select_org_puesto_id(int $cols, bool $con_registros, int $id_selected, PDO $link): array|string
    {
        $modelo = new org_puesto($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected, modelo: $modelo, label: "Puesto");
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    public function inputs_org_puesto(controlador_org_puesto $controlador_org_puesto): array|stdClass
    {
        $inputs = $this->genera_inputs_modifica(controler: $controlador_org_puesto, link: $controlador_org_puesto->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);
        }
        return $inputs;
    }
}
