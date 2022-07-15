<?php
namespace html;

use controllers\controlador_org_empresa;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use models\org_empresa;
use PDO;
use stdClass;

class org_empresa_html extends html_controler {


    private function asigna_inputs(controlador_org_empresa $controler, stdClass $inputs): array|stdClass
    {
        $controler->inputs->select = new stdClass();

        $controler->inputs->select->cat_sat_regimen_fiscal_id = $inputs->selects->cat_sat_regimen_fiscal_id;
        $controler->inputs->select->dp_pais_id = $inputs->selects->dp_pais_id;
        $controler->inputs->select->dp_estado_id = $inputs->selects->dp_estado_id;
        $controler->inputs->select->dp_municipio_id = $inputs->selects->dp_municipio_id;
        $controler->inputs->select->dp_cp_id = $inputs->selects->dp_cp_id;
        $controler->inputs->select->dp_colonia_postal_id = $inputs->selects->dp_colonia_postal_id;
        $controler->inputs->select->dp_calle_pertenece_id = $inputs->selects->dp_calle_pertenece_id;
        $controler->inputs->select->dp_calle_pertenece_entre1_id = $inputs->selects->dp_calle_pertenece_entre1_id;
        $controler->inputs->select->dp_calle_pertenece_entre2_id = $inputs->selects->dp_calle_pertenece_entre2_id;


        $controler->inputs->fecha_inicio_operaciones = $inputs->fechas->fecha_inicio_operaciones;
        $controler->inputs->fecha_ultimo_cambio_sat = $inputs->fechas->fecha_ultimo_cambio_sat;


        $controler->inputs->razon_social = $inputs->texts->razon_social;
        $controler->inputs->rfc = $inputs->texts->rfc;
        $controler->inputs->nombre_comercial = $inputs->texts->nombre_comercial;
        $controler->inputs->exterior = $inputs->texts->exterior;
        $controler->inputs->interior = $inputs->texts->interior;

        $controler->inputs->email_sat = $inputs->emails->email_sat;

        $controler->inputs->telefono_1 = $inputs->telefonos->telefono_1;
        $controler->inputs->telefono_2 = $inputs->telefonos->telefono_2;
        $controler->inputs->telefono_3 = $inputs->telefonos->telefono_2;

        return $controler->inputs;
    }

    public function em_email_sat(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {
        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->email_required(disable: false,name: 'email_sat',
            place_holder: 'Email SAT',row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    private function emails_alta(): array|stdClass
    {

        $emails = new stdClass();

        $em_email_sat = $this->em_email_sat(cols: 12,row_upd:
            new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $em_email_sat);
        }
        $emails->email_sat = $em_email_sat;


        return $emails;
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

    public function fec_fecha_ultimo_cambio_sat(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->fecha_required(disable: false,name: 'fecha_ultimo_cambio_sat',
            place_holder: 'Ultimo Cambio SAT',row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    private function fechas_alta(): array|stdClass
    {

        $fechas = new stdClass();

        $fec_fecha_inicio_operaciones = $this->fec_fecha_inicio_operaciones(cols: 6,row_upd:
            new stdClass(),value_vacio:  true);
        if(errores::$error){
           return $this->error->error(mensaje: 'Error al generar input',data:  $fec_fecha_inicio_operaciones);
        }
        $fechas->fecha_inicio_operaciones = $fec_fecha_inicio_operaciones;

        $fec_fecha_ultimo_cambio_sat = $this->fec_fecha_ultimo_cambio_sat(cols: 6,row_upd:
            new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $fec_fecha_ultimo_cambio_sat);
        }
        $fechas->fecha_ultimo_cambio_sat = $fec_fecha_ultimo_cambio_sat;
        return $fechas;
    }

    public function genera_inputs_alta(controlador_org_empresa $controler,PDO $link): array|stdClass
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

    public function genera_inputs_modifica(controlador_org_empresa $controler,PDO $link): array|stdClass
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
        $selects = $this->selects_alta(link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_alta();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }
        $fechas = $this->fechas_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs fecha',data:  $fechas);
        }

        $emails = $this->emails_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs fecha',data:  $emails);
        }

        $telefonos = $this->telefonos_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs $telefonos',data:  $telefonos);
        }

        $alta_inputs = new stdClass();
        
        $alta_inputs->texts = $texts;
        $alta_inputs->selects = $selects;
        $alta_inputs->fechas = $fechas;
        $alta_inputs->emails = $emails;
        $alta_inputs->telefonos = $telefonos;
        return $alta_inputs;
    }

    private function init_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {
        $selects = $this->selects_modifica(link: $link, row_upd: $row_upd);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar selects',data:  $selects);
        }

        $texts = $this->texts_alta();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar texts',data:  $texts);
        }
        $fechas = $this->fechas_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs fecha',data:  $fechas);
        }

        $emails = $this->emails_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs fecha',data:  $emails);
        }

        $telefonos = $this->telefonos_alta();

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar inputs $telefonos',data:  $telefonos);
        }

        $alta_inputs = new stdClass();

        $alta_inputs->texts = $texts;
        $alta_inputs->selects = $selects;
        $alta_inputs->fechas = $fechas;
        $alta_inputs->emails = $emails;
        $alta_inputs->telefonos = $telefonos;
        return $alta_inputs;
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

    public function input_nombre_comercial(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text_required(disable: false,name: 'nombre_comercial',
            place_holder: 'Nombre Comercial',row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->directivas->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    public function input_razon_social(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text_required(disable: false,name: 'razon_social',place_holder: 'Razon Social',row_upd: $row_upd,
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

    public function input_rfc(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        if($cols<=0){
            return $this->error->error(mensaje: 'Error cold debe ser mayor a 0', data: $cols);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cold debe ser menor o igual a  12', data: $cols);
        }

        $html =$this->directivas->input_text_required(disable: false,name: 'rfc',place_holder: 'RFC',row_upd: $row_upd,
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

    public function select_org_empresa_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new org_empresa($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

    private function selects_alta(PDO $link): array|stdClass
    {
        $selects = new stdClass();

        $select = (new cat_sat_regimen_fiscal_html())->select_cat_sat_regimen_fiscal_id(cols: 12, con_registros:true,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }


        $selects->cat_sat_regimen_fiscal_id = $select;


        $select = (new dp_pais_html())->select_dp_pais_id(cols: 6, con_registros:true,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_pais_id = $select;


        $select = (new dp_estado_html())->select_dp_estado_id(cols: 6, con_registros:false,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_estado_id = $select;

        $select = (new dp_municipio_html())->select_dp_municipio_id(cols: 6, con_registros:false,
            id_selected:-1,link:$link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_municipio_id = $select;


        $select = (new dp_cp_html())->select_dp_cp_id(cols: 6, con_registros:false,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_cp_id = $select;


        $select = (new dp_colonia_postal_html())->select_dp_colonia_postal_id(cols: 12, con_registros:false,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_colonia_postal_id = $select;


        $select = (new dp_calle_pertenece_html())->select_dp_calle_pertenece_id(cols: 12, con_registros:false,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_calle_pertenece_id = $select;

        $select = (new dp_calle_pertenece_html())->select_dp_calle_pertenece_entre1_id(cols: 6, con_registros:false,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_calle_pertenece_entre1_id = $select;

        $select = (new dp_calle_pertenece_html())->select_dp_calle_pertenece_entre2_id(cols: 6, con_registros:false,
            id_selected:-1,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_calle_pertenece_entre2_id = $select;

        return $selects;
    }

    private function selects_modifica(PDO $link, stdClass $row_upd): array|stdClass
    {
        $selects = new stdClass();

        $select = (new cat_sat_regimen_fiscal_html())->select_cat_sat_regimen_fiscal_id(cols: 12, con_registros:true,
            id_selected:$row_upd->cat_sat_regimen_fiscal_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);
        }


        $selects->cat_sat_regimen_fiscal_id = $select;


        $select = (new dp_pais_html())->select_dp_pais_id(cols: 6, con_registros:true,
            id_selected:$row_upd->dp_pais_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_pais_id = $select;


        $select = (new dp_estado_html())->select_dp_estado_id(cols: 6, con_registros:false,
            id_selected:$row_upd->dp_estado_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_estado_id = $select;

        $select = (new dp_municipio_html())->select_dp_municipio_id(cols: 6, con_registros:false,
            id_selected:$row_upd->dp_municipio_id,link:$link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_municipio_id = $select;


        $select = (new dp_cp_html())->select_dp_cp_id(cols: 6, con_registros:false,
            id_selected:$row_upd->dp_cp_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_cp_id = $select;


        $select = (new dp_colonia_postal_html())->select_dp_colonia_postal_id(cols: 12, con_registros:false,
            id_selected:$row_upd->dp_colonia_postal_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_colonia_postal_id = $select;


        $select = (new dp_calle_pertenece_html())->select_dp_calle_pertenece_id(cols: 12, con_registros:false,
            id_selected:$row_upd->dp_calle_pertenece_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_calle_pertenece_id = $select;

        $select = (new dp_calle_pertenece_html())->select_dp_calle_pertenece_entre1_id(cols: 6, con_registros:false,
            id_selected:$row_upd->dp_calle_pertenece_entre1_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_calle_pertenece_entre1_id = $select;

        $select = (new dp_calle_pertenece_html())->select_dp_calle_pertenece_entre2_id(cols: 6, con_registros:false,
            id_selected:$row_upd->dp_calle_pertenece_entre2_id,link: $link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select',data:  $select);

        }


        $selects->dp_calle_pertenece_entre2_id = $select;

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

    private function telefonos_alta(): array|stdClass
    {

        $telefonos = new stdClass();

        $telefono_1 = $this->telefono_1(cols: 4,row_upd:
            new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $telefono_1);
        }
        $telefonos->telefono_1 = $telefono_1;

        $telefono_2 = $this->telefono_2(cols: 4,row_upd:
            new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $telefono_2);
        }
        $telefonos->telefono_2 = $telefono_2;

        $telefono_3 = $this->telefono_3(cols: 4,row_upd:
            new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $telefono_3);
        }
        $telefonos->telefono_3 = $telefono_3;


        return $telefonos;
    }

    private function texts_alta(): array|stdClass
    {

        $texts = new stdClass();

        $in_razon_social = $this->input_razon_social(cols: 12,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_razon_social);
        }
        $texts->razon_social = $in_razon_social;

        $in_rfc = $this->input_rfc(cols: 6,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_razon_social);
        }
        $texts->rfc = $in_rfc;

        $in_nombre_comercial = $this->input_nombre_comercial(cols: 12,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_nombre_comercial);
        }
        $texts->nombre_comercial = $in_nombre_comercial;


        $in_exterior = $this->input_exterior(cols: 6,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_exterior);
        }
        $texts->exterior = $in_exterior;

        $in_interior = $this->input_interior(cols: 6,row_upd:  new stdClass(),value_vacio:  true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input',data:  $in_exterior);
        }
        $texts->interior = $in_interior;

        return $texts;
    }


}
