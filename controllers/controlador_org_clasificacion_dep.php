<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\organigrama\controllers;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_clasificacion_dep;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;
use html\org_clasificacion_dep_html;
use PDO;
use stdClass;

class controlador_org_clasificacion_dep extends _ctl_parent_sin_codigo {

    public array $keys_selects = array();

    public int $org_departamento_id = -1;
    public string $link_org_departamento_alta_bd = '';

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new org_clasificacion_dep(link: $link);
        $html_ = new org_clasificacion_dep_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:  $this->registro_id);

        $datatables = new stdClass();
        $datatables->columns = array();
        $datatables->columns['org_clasificacion_dep_id']['titulo'] = 'Id';
        $datatables->columns['org_clasificacion_dep_descripcion']['titulo'] = 'Clasificacion Depto';
        $datatables->columns['org_clasificacion_dep_n_departamentos']['titulo'] = 'Departamentos';

        $datatables->filtro = array();
        $datatables->filtro[] = 'org_clasificacion_dep.id';
        $datatables->filtro[] = 'org_clasificacion_dep.descripcion';

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Clasificacion de departamentos';


    }

    /**
     * Integra los keys para parametros de un select
     * @param array $keys_selects Keys precargados
     * @return array
     * @version 0.369.48
     */
    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 6,key: 'codigo', keys_selects:$keys_selects, place_holder: 'Cod');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(
            cols: 12,key: 'descripcion', keys_selects:$keys_selects, place_holder: 'Clas Depto');
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar key_selects',data:  $keys_selects);
        }

        return $keys_selects;
    }

}
