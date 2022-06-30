<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use models\org_empresa;
use PDO;

class org_empresa_html extends html_controler {

    public function select_org_empresa_id(int $id_selected, PDO $link): array|string
    {
        $modelo = new org_empresa($link);

        $select = $this->select_catalogo(id_selected:$id_selected, modelo: $modelo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }

}
