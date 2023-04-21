<?php
namespace gamboamartin\organigrama\models;
use base\orm\_modelo_parent_sin_codigo;
use gamboamartin\documento\models\doc_documento;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class org_logo extends _modelo_parent_sin_codigo{
    public function __construct(PDO $link){
        $tabla = 'org_logo';
        $columnas = array($tabla=>false,'org_empresa'=>$tabla,'doc_documento'=>$tabla,
            'doc_tipo_documento'=>'doc_documento','doc_extension'=>'doc_documento');
        $campos_obligatorios = array();

        $columnas_extra = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra);
        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Logos';
    }

    public function alta_bd(array $keys_integra_ds = array('descripcion')): array|stdClass
    {

        $org_empresa = (new org_empresa(link: $this->link))->registro(registro_id: $this->registro['org_empresa_id'], retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener org_empresa',data:  $org_empresa);
        }
        $name = $org_empresa->org_empresa_razon_social.time();

        $file['name'] = $name.$_FILES['logo']['name'];
        $file['tmp_name'] = $_FILES['logo']['tmp_name'];


        $data_doc = array();
        $data_doc['doc_tipo_documento_id'] = 9;

        $r_alta_doc_documento = (new doc_documento(link: $this->link))->alta_documento(registro: $data_doc,file: $file);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar documento',data:  $r_alta_doc_documento);
        }
        if(!isset($this->registro['descripcion'])){
            $descripcion = $org_empresa->org_empresa_rfc.time();
            $this->registro['descripcion'] = $descripcion;
        }


        $this->registro['doc_documento_id'] = $r_alta_doc_documento->registro_id;

        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar',data:  $r_alta_bd);
        }
        return $r_alta_bd;
    }
}