<?php /** @var stdClass $data */?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="img/favicon/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo " Administrador "; ?></title>
<link rel="stylesheet" href="node_modules/jquery-ui-dist/jquery-ui.css">
<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap-grid.css">
<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap-reboot.css">
<link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="node_modules/bootstrap-select/dist/css/bootstrap-select.css">

<script src="node_modules/jquery/dist/jquery.js"></script>
<script src="node_modules/jquery-ui-dist/jquery-ui.js"></script>
<script src="node_modules/popper.js/dist/umd/popper.js" ></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
<script src="node_modules/bootstrap-select/dist/js/bootstrap-select.js"></script>
<script src='node_modules/html5-qrcode/minified/html5-qrcode.min.js'></script>

    <!-- T E M P L A T E S -->

    <link rel="stylesheet" href="assets/libraries/font-awesome/css/font-awesome.min.css" />
    <!-- Start BOOTSTRAP -->
    <link rel="stylesheet" href="assets/libraries/tether/dist/css/tether.min.css" />
    <link rel="stylesheet" href="assets/libraries/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="assets/libraries/bootstrap-colorpicker-master/dist/css/bootstrap-colorpicker.min.css" />
    <!-- End Bootstrap -->
    <!-- Start Template files -->
    <link rel="stylesheet" href="assets/css/winter-flat.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />
    <!-- End  Template files -->
    <!-- Start owl-carousel -->
    <link rel="stylesheet" href="assets/libraries/owl.carousel/assets/owl.carousel.css" />
    <!-- End owl-carousel -->
    <!-- Start blueimp  -->
    <link rel="stylesheet" href="assets/css/blueimp-gallery.min.css" />
    <!-- End blueimp  -->
    <script src="assets/js/modernizr.custom.js"></script>
    <!-- Start custom template style  -->
    <link rel="stylesheet" href="assets/css/custom_template_style.css" />

    <!-- T E M P L A T E S -->

    <?php
echo $data->css_custom; ?>
<?php echo $data->js_view; ?>

</head>
<body>
    <?php include($data->include_action); ?>
    <?php //include 'views/org_puesto/alta.php'; ?>
    <?php //include 'views/org_puesto/lista.php'; ?>

    <?php //include "templates/proyecto.php"; ?>
    <?php //include "templates/comentarios.php"; ?>
    <?php //include "templates/menu_resumen.php"; ?>
    <?php //include "templates/tabla_resumen.php"; ?>
    <?php //include "templates/resumen.php"; ?>
    <?php //include "templates/hipoteca.php"; ?>
    <?php //include "templates/grafica.php"; ?>

</body>
</html>
