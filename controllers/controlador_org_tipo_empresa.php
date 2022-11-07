<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use gamboamartin\organigrama\models\org_tipo_empresa;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use gamboamartin\template\html;
use html\org_tipo_empresa_html;
use PDO;
use stdClass;

class controlador_org_tipo_empresa extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_tipo_empresa(link: $link);
        $html = new org_tipo_empresa_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);
        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Tipo EMpresa';

    }

}
