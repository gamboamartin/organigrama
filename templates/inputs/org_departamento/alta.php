<?php /** @var gamboamartin\organigrama\controllers\controlador_org_departamento $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->org_clasificacion_dep_id; ?>
<?php echo $controlador->inputs->org_empresa_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

