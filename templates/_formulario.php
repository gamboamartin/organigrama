<div class="row">
    <div class="col-lg-6">
        <div class="widget  widget-box box-container widget-form form-main" id="form">
            <div class="widget-header">
                <h2>Iniciar Sesión</h2>
            </div>
            <form action="#" method="post" class="form-additional">
                <!-- nombre de usuario -->
                <?php include 'templates/inputs/_text_nombre.php'; ?>
                <!-- contraseña -->
                <?php include 'templates/inputs/_text_contrasena.php'; ?>
                <!-- checkbox -->
                <?php include "templates/inputs/_checkbox.php"; ?>
                <!-- botones -->
                <?php include "templates/inputs/_boton_iniciar_sesion.php"; ?>
                <?php include "templates/inputs/_boton_limpiar.php"; ?>
            </form>
        </div>
    </div>
</div>