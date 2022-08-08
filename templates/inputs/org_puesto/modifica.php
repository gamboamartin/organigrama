<?php /** @var \gamboamartin\organigrama\controllers\controlador_org_puesto $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->forms_inputs_modifica; ?>
<?php echo $controlador->inputs->select->org_tipo_puesto_id; ?>
<?php echo $controlador->inputs->select->org_empresa_id; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>
