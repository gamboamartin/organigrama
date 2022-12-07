<?php
namespace gamboamartin\organigrama\controllers;
use base\controller\controlador_base;
use gamboamartin\errores\errores;
use gamboamartin\system\actions;

use stdClass;
use Throwable;

class _base {

    private errores $error;

    public function __construct(){
        $this->error = new errores();
    }



    public function data_retorno(string $tabla): array|stdClass
    {
        $seccion_retorno = $this->seccion_retorno(tabla: $tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener seccion_retorno',data:  $seccion_retorno);
        }

        $id_retorno = $this->id_retorno_init();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener id_retorno',data:  $id_retorno);
        }
        $data = new stdClass();

        $data->seccion_retorno = $seccion_retorno;
        $data->id_retorno = $id_retorno;

        return $data;

    }

    public function header(controlador_base $controler, bool $header, stdClass $result, stdClass $retorno, bool $ws): array|stdClass
    {
        $retorno = $this->id_retorno(result: $result,retorno:  $retorno);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener data retorno',data:  $retorno);
        }

        $return = $this->result(controler: $controler,header:  $header,result:  $result,retorno:  $retorno,ws:  $ws);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener retorno',data:  $return);
        }
        return $result;
    }


    private function id_retorno(stdClass $result, stdClass $retorno): stdClass
    {
        if((int)$retorno->id_retorno === -1){
            $retorno->id_retorno = $result->registro_id;
        }
        return $retorno;
    }

    private function id_retorno_init(){
        $id_retorno = -1;
        if(isset($_POST['id_retorno'])){
            $id_retorno = $_POST['id_retorno'];
        }
        return $id_retorno;
    }

    private function result(controlador_base $controler, bool $header, stdClass $result, stdClass $retorno, bool $ws): array|stdClass
    {
        $retorno_header = $this->result_header(controler: $controler, header: $header,result:  $result,retorno_data:  $retorno, ws: $ws);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener retorno',data:  $retorno_header);
        }

        $retorno_ws = $this->result_ws(result: $result, ws: $ws);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener retorno',data:  $retorno_ws);
        }
        return $result;
    }

    private function result_header(controlador_base $controler, bool $header, stdClass $result, stdClass $retorno_data, bool $ws){
        if($header){
            $retorno = (new actions())->retorno_alta_bd(link: $controler->link,registro_id:$retorno_data->id_retorno,
                seccion: $retorno_data->seccion_retorno, siguiente_view: $result->siguiente_view);
            if(errores::$error){
                return $controler->retorno_error(mensaje: 'Error al dar de alta registro', data: $result, header:  true,
                    ws: $ws);
            }
            header('Location:'.$retorno);
            exit;
        }
        return $result;
    }

    private function result_ws(stdClass $result, bool $ws){
        if($ws){
            header('Content-Type: application/json');
            try {
                echo json_encode($result, JSON_THROW_ON_ERROR);
            }
            catch (Throwable $e){
                $error = $this->error->error(mensaje: 'Error al dar salida',data:  $e);
                print_r($error);
                exit;
            }

            exit;
        }
        return $result;
    }

    /**
     * Obtiene la seccion de retorno
     * @param string $tabla Tabla o seccion en ejecucion
     * @return string|array
     * @version 0.374.48
     */
    private function seccion_retorno(string $tabla): string|array
    {
        $tabla = trim($tabla);
        if($tabla === ''){
            return $this->error->error(mensaje: 'Error tabla esta vacia',data:  $tabla);
        }
        $seccion_retorno = $tabla;
        if(isset($_POST['seccion_retorno'])){
            $seccion_retorno = $_POST['seccion_retorno'];
        }
        return $seccion_retorno;
    }
}