<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use html\org_sucursal_html;
use stdClass;


class org_sucursal_htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
    }

    /**
     */
    public function test_select_org_sucursal_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_GET['session_id'] = '1';
        $html_ = new html();
        $html = new org_sucursal_html($html_);
        //$html = new liberator($html);

        $cols = 1;
        $con_registros = true;
        $id_selected = -1;
        $link= $this->link;

        $resultado = $html->select_org_sucursal_id($cols, $con_registros, $id_selected, $link);

        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase("<div class='control-group col-sm-1'><label class='control-label' for='org_sucu", $resultado);
        $this->assertStringContainsStringIgnoringCase("g_sucursal_id'>Sucursal</label><div class='controls'><select class=", $resultado);
        $this->assertStringContainsStringIgnoringCase("s'><select class='form-control selectpicker color-secondary  org_sucursal_", $resultado);
        $this->assertStringContainsStringIgnoringCase("ucursal_id' data-live-search='true' id='org_sucursal_id' name='org_sucu", $resultado);

        errores::$error = false;
    }



}

