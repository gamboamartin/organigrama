<?php
namespace gamboamartin\organigrama\tests;
use base\orm\modelo_base;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_clasificacion_dep;
use gamboamartin\organigrama\models\org_departamento;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_puesto;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\organigrama\models\org_tipo_empresa;
use gamboamartin\organigrama\models\org_tipo_puesto;
use gamboamartin\organigrama\models\org_tipo_sucursal;
use PDO;


class base_test{

    public function alta_adm_seccion(PDO $link, string $descripcion = 'adm_seccion', int $id = 1): array|\stdClass
    {

        $alta = (new \gamboamartin\administrador\tests\base_test())->alta_adm_seccion(
            link: $link, descripcion: $descripcion, id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_dp_calle_pertenece(PDO $link, int $id = 1, string $predeterminado = 'inactivo'): array|\stdClass
    {

        $alta = (new \gamboamartin\direccion_postal\tests\base_test())->alta_dp_calle_pertenece(link: $link, id: $id,
            predeterminado: $predeterminado);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

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


    public function alta_org_empresa(PDO $link, int $dp_calle_pertenece_id = 1, int $id = 1,
                                     int $org_tipo_empresa_id = 1, int $org_tipo_sucursal_id = 1): array|\stdClass
    {

        $existe = (new org_tipo_sucursal($link))->existe_by_id(registro_id: $org_tipo_sucursal_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe ', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_org_tipo_sucursal(link: $link, id: $org_tipo_sucursal_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }


        $existe = (new dp_calle_pertenece($link))->existe_by_id(registro_id: $dp_calle_pertenece_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe ', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_dp_calle_pertenece(link: $link, id: $dp_calle_pertenece_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }

        $existe = (new org_tipo_empresa($link))->existe_by_id(registro_id: $org_tipo_empresa_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe ', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_org_tipo_empresa(link: $link, id: $org_tipo_empresa_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 'ESCUELA KEMPER URGATE';
        $registro['razon_social'] = 'ESCUELA KEMPER URGATE';
        $registro['rfc'] = 'EKU9003173C9';
        $registro['nombre_comercial'] = 'ESCUELA KEMPER URGATE';
        $registro['org_tipo_empresa_id'] = $org_tipo_empresa_id;
        $registro['dp_calle_pertenece_id'] = $dp_calle_pertenece_id;


        $alta = (new org_empresa($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_puesto(PDO $link, int $org_departamento_id = 1, int $org_tipo_puesto_id = 1,
                                    string $predeterminado = 'inactivo'): array|\stdClass
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

        $existe = (new org_tipo_puesto($link))->existe_by_id(registro_id: $org_tipo_puesto_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al validar si existe ', data: $existe);
        }
        if(!$existe){
            $alta = $this->alta_org_tipo_puesto(link: $link, id: $org_tipo_puesto_id);
            if(errores::$error){
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }


        $registro = array();
        $registro['id'] = 1;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;
        $registro['org_tipo_puesto_id'] = $org_tipo_puesto_id;
        $registro['org_departamento_id'] = $org_departamento_id;
        $registro['predeterminado'] = $predeterminado;


        $alta = (new org_puesto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_sucursal(PDO $link, int $id = 1, int $org_empresa_id = 1, int $org_tipo_sucursal_id = 1): array|\stdClass
    {

        $existe = (new org_tipo_sucursal($link))->existe_by_id(registro_id: $org_tipo_sucursal_id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al verificar si existe ', data: $existe);
        }
        if(!$existe) {
            $alta = $this->alta_org_tipo_sucursal(link: $link, id: $org_tipo_sucursal_id);
            if (errores::$error) {
                return (new errores())->error(mensaje: 'Error al insertar ', data: $alta);
            }
        }

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
        $registro['org_tipo_sucursal_id'] = $org_tipo_sucursal_id;

        $alta = (new org_sucursal($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_tipo_empresa(PDO $link, int $id = 1): array|\stdClass
    {


        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;


        $alta = (new org_tipo_empresa($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_tipo_puesto(PDO $link, int $id = 1): array|\stdClass
    {


        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = 1;
        $registro['descripcion'] = 1;


        $alta = (new org_tipo_puesto($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_org_tipo_sucursal(PDO $link, string $codigo = 'MAT', string $descripcion= 'MATRIZ',
                                           int $id = 1): array|\stdClass
    {


        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['descripcion'] = $descripcion;


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


    public function del_adm_seccion(PDO $link): array
    {

        $del = (new \gamboamartin\administrador\tests\base_test())->del_adm_seccion(link: $link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_cat_sat_regimen_fiscal(PDO $link): array
    {


        $del = (new \gamboamartin\cat_sat\tests\base_test())->del_cat_sat_regimen_fiscal($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        return $del;
    }
    public function del_dp_calle_pertenece(PDO $link): array
    {


        $del = (new base_test())->del_org_empresa($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }

        $del = (new \gamboamartin\direccion_postal\tests\base_test())->del_dp_calle_pertenece($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        return $del;
    }

    public function del_dp_pais(PDO $link): array
    {

        $del = (new \gamboamartin\direccion_postal\tests\base_test())->del_dp_pais($link);
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

    public function del_org_ejecuta(PDO $link): array
    {


        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_ejecuta');
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

        $del = $this->del_org_logo($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }


        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_empresa');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_logo(PDO $link): array
    {

        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_logo');
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

        $del = $this->del_org_ejecuta($link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar ', data: $del);
        }

        $del = $this->del($link, 'gamboamartin\\organigrama\\models\\org_puesto');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_sucursal(PDO $link): array
    {

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
