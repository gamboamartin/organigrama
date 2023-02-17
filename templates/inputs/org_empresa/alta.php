<?php /** @var gamboamartin\organigrama\controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views;

include 'templates/inputs/_base/org_empresa/form.php' ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

<div class="col-md-12">
    <?php
    foreach ($controlador->buttons_parents_alta as $button){ ?>
        <div class="col-md-4">
            <?php echo $button; ?>
        </div>
    <?php } ?>
</div>

