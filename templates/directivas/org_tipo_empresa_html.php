<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use models\org_tipo_empresa;
use PDO;


class org_tipo_empresa_html extends html_controler {

    /**
     * @param int $cols
     * @param bool $con_registros
     * @param int $id_selected
     * @param PDO $link
     * @return array|string
     */
    public function select_org_tipo_empresa_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new org_tipo_empresa($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Tipo empresa',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
