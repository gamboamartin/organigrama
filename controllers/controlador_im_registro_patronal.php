<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;


use PDO;
use stdClass;


class controlador_im_registro_patronal extends \gamboamartin\im_registro_patronal\controllers\controlador_im_registro_patronal {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){


        parent::__construct(link: $link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Registro Patronal';

    }


}
