<?php /** @var controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->codigo_bis; ?>

<?php echo $controlador->inputs->fecha_inicio_operaciones; ?>

<?php echo $controlador->inputs->select->dp_calle_pertenece_id; ?>
<?php echo $controlador->inputs->select->dp_calle_pertenece_entre1_id; ?>
<?php echo $controlador->inputs->select->dp_calle_pertenece_entre2_id; ?>
<?php echo $controlador->inputs->select->org_empresa_id; ?>

<?php echo $controlador->inputs->exterior; ?>
<?php echo $controlador->inputs->interior; ?>

<?php echo $controlador->inputs->telefono_1; ?>
<?php echo $controlador->inputs->telefono_2; ?>
<?php echo $controlador->inputs->telefono_3; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
