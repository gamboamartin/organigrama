<?php /** @var controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->razon_social; ?>


<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
