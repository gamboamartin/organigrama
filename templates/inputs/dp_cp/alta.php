<?php /** @var controllers\controlador_dp_estado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->forms_inputs_alta; ?>
<?php echo $controlador->inputs->dp_estado_id; ?>
<?php echo $controlador->inputs->dp_municipio_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/dp_estado_alta.php';?></div>
</div>
