<?php

namespace controllers;

use base\frontend\values;
use config\generales;
use gamboamartin\errores\errores;
use html\html;
use JetBrains\PhpStorm\NoReturn;
use models\org_puesto;
use PDO;
use base\controller\controlador_base;
use stdClass;

class controlador_org_puesto  extends controlador_base{

    public string $include_menu = '';
    public string $link_inicio = '';
    private html $html;

    public function __construct(PDO $link){
        $modelo = new org_puesto(link: $link);
        parent::__construct(link: $link,modelo:  $modelo);
    }

    public function alta(bool $header = true, bool $ws = false): string|array
    {
        $this->include_menu = (new generales())->path_base;
        $this->include_menu .= 'templates/_alta.php';

        return $this->include_menu;
    }

    public function lista(bool $header = true, bool $ws = false): array
    {

        $this->include_menu = (new generales())->path_base;
        $this->include_menu .= 'templates/_lista.php';

        $org_puesto = $this->modelo->registros();
        if(errores::$error) {
            $error = $this->retorno_error(mensaje: 'Error al obtener lista', data: $org_puesto,
                header: $header, ws: $ws);
            print_r($error);
            die('Error');
        }
        $this->registros = $org_puesto;

        return $this->registros;
    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true, bool $muestra_btn = true): array|string
    {
        $this->include_menu = (new generales())->path_base;
        $this->include_menu .= 'templates/_modifica.php';

        return $this->include_menu;
        //return parent::modifica($header, $ws, $breadcrumbs, $aplica_form, $muestra_btn); // TODO: Change the autogenerated stub
    }

    #[NoReturn] public function alta_bd(bool $header, bool $ws): array|stdClass
    {
        $this ->link ->beginTransaction();
        $r_alta_org_puesto = $this->modelo->alta_registro(registro: $_POST);
        if(errores::$error) {
            $this->link->rollBack();
            $error = $this->retorno_error(mensaje: 'Error al guardar registro', data: $r_alta_org_puesto,
                header: $header, ws: $ws);
            print_r($error);
            die('Error');
        }
        //header
        $this->link->commit();
        exit;
    }
}