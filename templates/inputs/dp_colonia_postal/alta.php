<?php /** @var controllers\controlador_dp_estado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->forms_inputs_alta; ?>
<?php echo $controlador->inputs->select->dp_cp_id; ?>
<?php echo $controlador->inputs->select->dp_colonia_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/dp_colonia_alta.php';?></div>
</div>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/dp_cp_alta.php';?></div>
</div>
