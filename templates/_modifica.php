<div class="row">
    <div class="col-lg-6">
        <div class="widget  widget-box box-container widget-form form-main" id="form">
            <div class="widget-header">
                <h2>Modifica ORG Puesto</h2>
            </div>
            <form action="#" method="post" class="form-additional">

                <div class="control-group">
                    <label class="control-label" for="inputUsername2">Descripción</label>
                    <div class="controls">
                        <input type="text" name="descripcion" value="" class="form-control" id="descripcion" placeholder="Descripción">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="inputPassword1">Código</label>
                    <div class="controls">
                        <input type="password" name="codigo" value="" class="form-control" id="codigo" placeholder="Código" />
                    </div>
                </div>

                <?php include "templates/org_puesto/_checkbox.php"; ?>

                <?php include "templates/org_puesto/_select.php"; ?>

                <?php include "templates/org_puesto/_boton_guardar.php"; ?>

            </form>
        </div>
    </div>
</div>