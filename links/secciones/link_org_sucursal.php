<?php
namespace gamboamartin\organigrama\links\secciones;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use stdClass;
use PDO;

class link_org_sucursal extends links_menu {
    public stdClass $links;


    private function link_org_sucursal_alta(): array|string
    {
        $org_sucursal_alta = $this->org_sucursal_alta();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener link de org_sucursal alta', data: $org_sucursal_alta);
        }

        $org_sucursal_alta.="&session_id=$this->session_id";
        return $org_sucursal_alta;
    }

    protected function links(PDO $link,int $registro_id): stdClass|array
    {

        $links =  parent::links(link: $link,registro_id: $registro_id); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar links', data: $links);
        }

        $org_sucursal_alta = $this->link_org_sucursal_alta();
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar link', data: $org_sucursal_alta);
        }
        if(!isset($this->links->org_sucursal)){
            $this->links->org_sucursal = new stdClass();
        }
        $this->links->org_sucursal->nueva_sucursal = $org_sucursal_alta;

        return $links;
    }

    /**
     * Genera un link a empresa alta sin session_id
     * @return string Un link de tipo seccion org_empresa accion alta
     * @version 0.6.0
     */
    private function org_sucursal_alta(): string
    {
        return "./index.php?seccion=org_sucursal&accion=alta";
    }


}
