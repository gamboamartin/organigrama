<?php /** @var gamboamartin\organigrama\controllers\controlador_org_sucursal $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <form method="post" action="<?php echo $controlador->link_alta_bd; ?>" class="form-additional">

                    <?php include (new views())->ruta_templates."head/title.php"; ?>
                        <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                        <?php include (new views())->ruta_templates."mensajes.php"; ?>
                    <?php echo $controlador->inputs->select->org_empresa_id; ?>
                    <?php echo $controlador->inputs->codigo; ?>
                    <?php echo $controlador->inputs->codigo_bis; ?>

                    <?php echo $controlador->inputs->fecha_inicio_operaciones; ?>

                    <?php echo $controlador->inputs->select->dp_pais_id; ?>
                    <?php echo $controlador->inputs->select->dp_estado_id; ?>
                    <?php echo $controlador->inputs->select->dp_municipio_id; ?>
                    <?php echo $controlador->inputs->select->dp_cp_id; ?>
                    <?php echo $controlador->inputs->select->dp_colonia_postal_id; ?>
                    <?php echo $controlador->inputs->select->dp_calle_pertenece_id; ?>



                    <?php echo $controlador->inputs->exterior; ?>
                    <?php echo $controlador->inputs->interior; ?>

                    <?php echo $controlador->inputs->telefono_1; ?>
                    <?php echo $controlador->inputs->telefono_2; ?>
                    <?php echo $controlador->inputs->telefono_3; ?>

                    <?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>

                    <div class="control-group btn-alta col-12">
                        <div class="controls">
                            <?php include 'templates/botons/org_empresa_alta.php';?>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>





