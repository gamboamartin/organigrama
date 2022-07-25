<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 0.48.13
 * @verclass 1.0.0
 * @created 2022-07-25
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;


use PDO;


class controlador_dp_colonia_postal extends \controllers\controlador_dp_colonia_postal {

    public function __construct(PDO $link){


        parent::__construct(link: $link);

        $this->titulo_lista = 'Colonias con códigos postales';

    }


}
