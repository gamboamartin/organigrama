<?php

namespace controllers;

use config\generales;
use gamboamartin\errores\errores;
use html\html;
use models\org_puesto;
use PDO;
use base\controller\controlador_base;

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
        $this->include_menu .= 'templates/_formulario';

        return $this->include_menu;
    }
}