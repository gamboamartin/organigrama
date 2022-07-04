<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace controllers;

use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use html\org_actividad_html;
use models\org_actividad;
use PDO;
use stdClass;

class controlador_org_actividad extends system {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new org_actividad(link: $link);
        $html = new org_actividad_html();
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Actividades';

    }




}
