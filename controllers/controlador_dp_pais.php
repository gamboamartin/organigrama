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


class controlador_dp_pais extends \controllers\controlador_dp_pais {

    public function __construct(PDO $link){


        parent::__construct(link: $link);

        $this->titulo_lista = 'Regimenes fiscales';

    }


}
