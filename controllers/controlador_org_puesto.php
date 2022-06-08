<?php

namespace controllers;

use models\org_puesto;
use PDO;
use base\controller\controlador_base;

class controlador_org_puesto  extends controlador_base{
    public function __construct(PDO $link){
        $modelo = new org_puesto(link: $link);
        parent::__construct(link: $link,modelo:  $modelo);
    }
}