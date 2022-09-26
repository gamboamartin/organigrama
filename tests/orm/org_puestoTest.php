<?php
namespace tests\orm;

use gamboamartin\errores\errores;
use gamboamartin\organigrama\models\org_puesto;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\test\test;
use stdClass;
use tests\base_test;


class org_puestoTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/cat_sat/config/generales.php';
        $this->paths_conf->database = '/var/www/html/cat_sat/config/database.php';
        $this->paths_conf->views = '/var/www/html/cat_sat/config/views.php';


    }

    /**
     */
    public function test_get_puesto_default_id(): void
    {
        errores::$error = false;

        $_SESSION['usuario_id'] = 2;
        $modelo = new org_puesto(link: $this->link);
        //$lim = new liberator($lim);


        $del = (new base_test())->del_org_departamento($this->link);
        if(errores::$error){
            $error = (new errores())->error('Error al eliminar', $del);
            print_r($error);
            exit;
        }


        $alta = (new base_test())->alta_org_puesto(link: $this->link, predeterminado : 'activo');
        if(errores::$error){
            $error = (new errores())->error('Error al insertar', $alta);
            print_r($error);
            exit;
        }

        $resultado = $modelo->get_puesto_default_id();

        $this->assertIsInt($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }


}

