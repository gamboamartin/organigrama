<?php
namespace tests\links\secciones;

use gamboamartin\administrador\models\adm_namespace;
use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\errores\errores;
use gamboamartin\organigrama\controllers\controlador_org_empresa;

use gamboamartin\test\test;



use stdClass;


class empresasTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/organigrama/config/generales.php';
        $this->paths_conf->database = '/var/www/html/organigrama/config/database.php';
        $this->paths_conf->views = '/var/www/html/organigrama/config/views.php';


    }

    /**
     */
    public function test_limpia_post_dp(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'org_empresa';
        $_GET['accion'] = 'ubicacion';

        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $_SESSION['usuario_id'] = '2';

        unset($_POST['seccion_retorno']);

        $del = (new adm_namespace(link: $this->link))->elimina_todo();
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al eliminar namespace', data: $del);
            print_r($error);
            exit;
        }

        $adm_namespace['id'] = 1;
        $adm_namespace['descripcion'] = 'gamboa.martin/acl';

        $alta = (new adm_namespace(link: $this->link))->alta_registro($adm_namespace);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar namespace', data: $alta);
            print_r($error);
            exit;
        }

        $adm_seccion['id'] = 1;
        $adm_seccion['descripcion'] = 'org_empresa';
        $adm_seccion['adm_menu_id'] = '1';

        $alta = (new adm_seccion(link: $this->link))->alta_registro($adm_seccion);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar seccion', data: $alta);
            print_r($error);
            exit;
        }


        $ctl = new controlador_org_empresa(link: $this->link, paths_conf: $this->paths_conf);
        //$ctl = new liberator($ctl);
        $resultado = $ctl->limpia_post_dp();

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEmpty($resultado);

        errores::$error = false;

        $_POST['X'] = 'X';


        $resultado = $ctl->limpia_post_dp();
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotEmpty($resultado);

        errores::$error = false;

        $_POST['dp_pais_id'] = 'X';


        $resultado = $ctl->limpia_post_dp();
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotEmpty($resultado);


        errores::$error = false;
    }



}

