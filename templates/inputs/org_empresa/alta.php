<?php /** @var controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->rfc; ?>
<?php echo $controlador->inputs->razon_social; ?>
<?php echo $controlador->inputs->nombre_comercial; ?>
<?php echo $controlador->inputs->select->cat_sat_regimen_fiscal_id; ?>
<?php echo $controlador->inputs->select->dp_pais_id; ?>
<?php echo $controlador->inputs->select->dp_estado_id; ?>
<?php echo $controlador->inputs->select->dp_municipio_id; ?>
<?php echo $controlador->inputs->select->dp_cp_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/cat_sat_regimen_fiscal_alta.php';?></div>
</div>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/dp_pais_alta.php';?></div>
</div>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/dp_estado_alta.php';?></div>
</div>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/dp_municipio_alta.php';?></div>
</div>

<div class="control-group btn-alta">
    <div class="controls"><?php include 'templates/botons/dp_cp_alta.php';?></div>
</div>
