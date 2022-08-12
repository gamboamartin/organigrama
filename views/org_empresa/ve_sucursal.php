<?php /** @var gamboamartin\organigrama\controllers\controlador_org_empresa $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">


                    <?php include (new views())->ruta_templates."head/title.php"; ?>
                    <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates."mensajes.php"; ?>

                    <?php echo $controlador->inputs->select->org_empresa_id; ?>

                    <?php echo $controlador->inputs->codigo; ?>
                    <?php echo $controlador->inputs->codigo_bis; ?>
                    <?php echo $controlador->inputs->razon_social; ?>


                </div>

            </div>

        </div>
    </div>




</main>





