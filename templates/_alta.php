<?php
/** @var controllers\controlador_org_puesto $controlador */
?>
<br>
<div class="row">
    <div class="col-lg-6">
        <div class="widget  widget-box box-container widget-form form-main" id="form">
            <div class="widget-header">
                <h2>Alta ORG Puesto</h2>
            </div>
            <form action="./index.php?seccion=org_puesto&accion=alta_db&accion=alta_bd&session_id=<?php echo $controlador->session_id; ?>" method="POST" class="form-additional">

                <div class="control-group">
                    <label class="control-label" for="descripcion">Descripción</label>
                    <div class="controls">
                        <input type="text" name="descripcion" value="" class="form-control" id="descripcion" placeholder="Descripción">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="codigo">Código</label>
                    <div class="controls">
                        <input type="password" name="codigo" value="" class="form-control" id="codigo" placeholder="Código" />
                    </div>
                </div>

                <?php include "templates/org_puesto/_checkbox.php"; ?>

                <?php include "templates/org_puesto/_select.php"; ?>

                <?php include "templates/org_puesto/_boton_guardar.php"; ?>

            </form>
        </div>
    </div>
</div>