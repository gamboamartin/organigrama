<?php
namespace gamboamartin\organigrama\models;
use base\orm\_defaults;
use base\orm\_modelo_parent_sin_codigo;

use gamboamartin\errores\errores;
use PDO;


class org_tipo_sucursal extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_tipo_sucursal';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();


        $campos_view['codigo'] = array('type' => 'inputs');
        $campos_view['descripcion'] = array('type' => 'inputs');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Tipo Sucursal';

        if(!isset($_SESSION['init'][$tabla])) {
            $catalago = array();
            $catalago[] = array('codigo' => 'MATRIZ', 'descripcion' => 'MATRIZ');
            $catalago[] = array('codigo' => 'SUCURSAL', 'descripcion' => 'SUCURSAL');


            $r_alta_bd = (new _defaults())->alta_defaults(catalago: $catalago, entidad: $this);
            if (errores::$error) {
                $error = $this->error->error(mensaje: 'Error al insertar', data: $r_alta_bd);
                print_r($error);
                exit;
            }
            $_SESSION['init'][$tabla] = true;
        }

    }

}