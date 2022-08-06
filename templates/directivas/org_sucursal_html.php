<?php
namespace html;


use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_sucursal;
use gamboamartin\system\html_controler;
use models\base\limpieza;
use models\base\rows;
use PDO;
use stdClass;


class org_sucursal_html extends html_controler {


    private function asigna_inputs(controlador_org_sucursal $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->org_empresa_id = $inputs->selects->org_empresa_id;
        $controler->inputs->select->dp_pais_id = $inputs->selects->dp_pais_id;
        $controler->inputs->select->dp_estado_id = $inputs->selects->dp_estado_id;
        $controler->inputs->select->dp_municipio_id = $inputs->selects->dp_municipio_id;
        $controler->inputs->select->dp_cp_id = $inputs->selects->dp_cp_id;
        $controler->inputs->select->dp_colonia_postal_id = $inputs->selects->dp_colonia_postal_id;
        $controler->inputs->select->dp_calle_pertenece_id = $inputs->selects->dp_calle_pertenece_id;

        $controler->inputs->fecha_inicio_operaciones = $inputs->fechas->fecha_inicio_operaciones;

        $controler->inputs->exterior = $inputs->texts->exterior;
        $controler->inputs->interior = $inputs->texts->interior;
        $controler->inputs->codigo = $inputs->texts->codigo;
        $controler->inputs->codigo_bis = $inputs->texts->codigo_bis;

        $controler->inputs->telefono_1 = $inputs->telefonos->telefono_1;
        $controler->inputs->telefono_2 = $inputs->telefonos->telefono_2;
        $controler->inputs->telefono_3 = $inputs->telefonos->telefono_3;

        return $controler->inputs;
    }

    private function fechas_alta(stdClass $row_upd = new stdClass()): array|stdClass
    {

        $fechas = new stdClass();

        $fec_fecha_inicio_operaciones = $this->fec_fecha_inicio_operaciones(cols: 4,row_upd: $row_upd,
            value_vacio:  false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $fec_fecha_inicio_operaciones);
        }
        $fechas->fecha_inicio_operaciones = $fec_fecha_inicio_operaciones;

        return $fechas;
    }

    public function fec_fecha_inicio_operaciones(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->fecha_required(disable: false,name: 'fecha_inicio_operaciones',
            place_holder: 'Inicio de Operaciones',row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function genera_inputs_alta(controlador_org_sucursal $controler,PDO $link): array|stdClass
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

    private function genera_inputs_modifica(controlador_org_sucursal $controler,PDO $link): array|stdClass
    {
        $inputs = $this->init_modifica(link: $link, row_upd: $controler->row_upd);
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
        $row_upd = new stdClass();
        $selects = $this->selects_alta(link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $fechas = $this->fechas_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs fecha',data:  $fechas);
        }

        $texts = $this->texts_alta(row_upd: $row_upd,value_vacio: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }

        $telefonos = $this->telefonos_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs $telefonos',data:  $telefonos);
        }

        $alta_inputs = new stdClass();
        $alta_inputs->texts = $texts;
        $alta_inputs->fechas = $fechas;
        $alta_inputs->selects = $selects;
        $alta_inputs->telefonos = $telefonos;
        return $alta_inputs;
    }

    private function init_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {


        $selects = $this->selects_modifica(link: $link, row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_alta(row_upd: $row_upd, value_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }
        $fechas = $this->fechas_alta(row_upd: $row_upd);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs fecha',data:  $fechas);
        }

        $telefonos = $this->telefonos_alta(row_upd: $row_upd);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs $telefonos',data:  $telefonos);
        }

        $alta_inputs = new stdClass();

        $alta_inputs->texts = $texts;
        $alta_inputs->selects = $selects;
        $alta_inputs->fechas = $fechas;
        $alta_inputs->telefonos = $telefonos;
        return $alta_inputs;
    }

    public function input_codigo(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text_required(disable: false,name: 'codigo',place_holder: 'Codigo',row_upd: $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_codigo_bis(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text_required(disable: false,name: 'codigo_bis',place_holder: 'Codigo BIS',
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_exterior(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text_required(disable: false,name: 'exterior',place_holder: 'Num Ext',row_upd: $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_interior(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text(disable: false,name: 'interior',place_holder: 'Num Int', required: false,
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function inputs_org_sucursal(controlador_org_sucursal $controlador_org_sucursal): array|stdClass
    {
        $init = (new limpieza())->init_modifica_org_sucursal(controler: $controlador_org_sucursal);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializa datos',data:  $init);
        }

        $inputs = $this->genera_inputs_modifica(controler: $controlador_org_sucursal, link: $controlador_org_sucursal->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs',data:  $inputs);
        }
        return $inputs;
    }

    private function selects_alta(PDO $link): array|stdClass
    {
        $selects = new stdClass();

        $row_upd = new stdClass();

        $selects = (new selects())->direcciones(html: $this->html_base,link:  $link,row:  $row_upd,selects:  $selects);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects de domicilios',data:  $selects);

        }


        $select = (new org_empresa_html($this->html_base))->select_org_empresa_id(cols: 12, con_registros:true,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }

        $selects->org_empresa_id = $select;

        return $selects;
    }

    private function selects_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {

        $selects = new stdClass();


        $selects = (new selects())->direcciones(html: $this->html_base,link:  $link,row:  $row_upd,selects:  $selects);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects de domicilios',data:  $selects);

        }
        $select = (new org_empresa_html($this->html_base))->select_org_empresa_id(cols: 12, con_registros:true,
            id_selected:$row_upd->org_empresa_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }

        $selects->org_empresa_id = $select;

        return $selects;
    }

    public function telefono_1(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {
        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text_required(disable: false,name: 'telefono_1',
            place_holder: 'Telefono 1',row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function telefono_2(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {
        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text(disable: false,name: 'telefono_2',
            place_holder: 'Telefono 2',required: false,row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function telefono_3(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {
        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text(disable: false,name: 'telefono_3',
            place_holder: 'Telefono 3',required: false,row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    private function telefonos_alta(stdClass $row_upd = new stdClass()): array|stdClass
    {

        $telefonos = new stdClass();

        $telefono_1 = $this->telefono_1(cols: 4,row_upd: $row_upd,value_vacio:  false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $telefono_1);
        }
        $telefonos->telefono_1 = $telefono_1;

        $telefono_2 = $this->telefono_2(cols: 4,row_upd: $row_upd,value_vacio:  false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $telefono_2);
        }
        $telefonos->telefono_2 = $telefono_2;

        $telefono_3 = $this->telefono_3(cols: 4,row_upd: $row_upd,value_vacio:  false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $telefono_3);
        }
        $telefonos->telefono_3 = $telefono_3;


        return $telefonos;
    }

    private function texts_alta(stdClass $row_upd, bool $value_vacio): array|stdClass
    {

        $texts = new stdClass();

        $in_exterior = $this->input_exterior(cols: 6,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_exterior);
        }
        $texts->exterior = $in_exterior;

        $in_interior = $this->input_interior(cols: 6,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_exterior);
        }
        $texts->interior = $in_interior;

        $in_codigo = $this->input_codigo(cols: 4,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_codigo);
        }
        $texts->codigo = $in_codigo;

        $in_codigo_bis = $this->input_codigo_bis(cols: 4,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_codigo_bis);
        }
        $texts->codigo_bis = $in_codigo_bis;



        return $texts;
    }
}
