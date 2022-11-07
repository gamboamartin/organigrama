<?php
namespace gamboamartin\organigrama\tests;
use base\orm\modelo_base;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_clasificacion_dep;
use gamboamartin\organigrama\models\org_departamento;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_puesto;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\organigrama\models\org_tipo_sucursal;
use PDO;


class base_test{

    public function alta_org_clasificacion_dep(PDO $link): array|\stdClass
    {
        $registro = array();
        $registro['id'] = 1;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;



        $alta = (new org_clasificacion_dep($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }


    public function alta_org_departamento(PDO $link, int $id = 1,int $org_clasificacion_dep_id = 1): array|\stdClass
    {

        $existe = (new org_clasificacion_dep($link))->existe_by_id(registro_id: $org_clasificacion_dep_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe ', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_org_clasificacion_dep($link);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;
        $registro['org_clasificacion_dep_id'] = $org_clasificacion_dep_id;


        $alta = (new org_departamento($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }


    public function alta_org_empresa(PDO $link, int $id = 1): array|\stdClass
    {
        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;
        $registro['razon_social'] = 1;
        $registro['rfc'] = 'AAA010101ABC';
        $registro['nombre_comercial'] = 1;
        $registro['org_tipo_empresa_id'] = 1;


        $alta = (new org_empresa($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_puesto(PDO $link, int $org_departamento_id = 1, string $predeterminado = 'inactivo'): array|\stdClass
    {


        $existe = (new org_departamento($link))->existe_by_id(registro_id: $org_departamento_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe ', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_org_departamento(link: $link, id: $org_departamento_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }


        $registro = array();
        $registro['id'] = 1;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;
        $registro['org_tipo_puesto_id'] = 1;
        $registro['org_departamento_id'] = $org_departamento_id;
        $registro['predeterminado'] = $predeterminado;


        $alta = (new org_puesto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_sucursal(PDO $link, int $id = 1, int $org_empresa_id = 1): array|\stdClass
    {
        $existe = (new org_empresa($link))->existe_by_id(registro_id: $org_empresa_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al verificar si existe ', data: $existe);
        }
        if(!$existe) {
            $alta = $this->alta_org_empresa(link: $link, id: $org_empresa_id);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
            $del = $this->del_org_sucursal($link);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
            }
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;
        $registro['org_empresa_id'] = $org_empresa_id;


        $alta = (new org_sucursal($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_tipo_sucursal(PDO $link): array|\stdClass
    {


        $registro = array();
        $registro['id'] = 1;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;


        $alta = (new org_tipo_sucursal($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }





    public function del(PDO $link, string $name_model): array
    {

        $model = (new modelo_base($link))->genera_modelo(modelo: $name_model);
        $del = $model->elimina_todo();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar '.$name_model, data: $del);
        }
        return $del;
    }

    public function del_fc_csd(PDO $link): array
    {

        $del = (new base_test())->del_im_registro_patronal($link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }
        $del = (new \gamboamartin\facturacion\tests\base_test())->del_fc_csd($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        return $del;
    }

    public function del_im_registro_patronal(PDO $link): array
    {

        $del = (new \gamboamartin\im_registro_patronal\test\base_test())->del_im_registro_patronal($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        return $del;
    }

    public function del_org_clasificacion_dep(PDO $link): array
    {

        $del = $this->del_org_departamento($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }
        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_clasificacion_dep');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_departamento(PDO $link): array
    {

        $del = $this->del_org_puesto($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_departamento');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_empresa(PDO $link): array
    {

        $del = $this->del_org_porcentaje_act_economica($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del_org_puesto($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del_org_departamento($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del_org_sucursal($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_empresa');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_porcentaje_act_economica(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_porcentaje_act_economica');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_puesto(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_puesto');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_sucursal(PDO $link): array
    {

        $del = $this->del_fc_csd($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_sucursal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_tipo_sucursal(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_tipo_sucursal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }



}
