<?php /** @var gamboamartin\organigrama\controllers\controlador_org_representante_legal $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->nombre; ?>
<?php echo $controlador->inputs->ap_paterno; ?>
<?php echo $controlador->inputs->ap_materno; ?>
<?php echo $controlador->inputs->rfc; ?>

<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>

<div class="col-row-12">
    <?php foreach ($controlador->buttons as $button){ ?>
        <?php echo $button; ?>
    <?php }?>
</div>
