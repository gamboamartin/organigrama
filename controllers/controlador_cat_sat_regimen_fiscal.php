<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;
use gamboamartin\cat_sat\models\cat_sat_regimen_fiscal;
use PDO;
use stdClass;


class controlador_cat_sat_regimen_fiscal extends \gamboamartin\cat_sat\controllers\controlador_cat_sat_regimen_fiscal {

    public function __construct(PDO $link , stdClass $paths_conf = new stdClass()){


        parent::__construct(link: $link,  paths_conf: $paths_conf);

        $this->titulo_lista = 'Regimenes fiscales';

    }


}
