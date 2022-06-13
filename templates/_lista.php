<div class="widget widget-box box-container widget-mylistings">
    <div class="widget-header text-uppercase">
        <h2>Lista</h2>
    </div>
    <table class="table table-striped footable-sort" data-sorting="true" style="display: table;">
        <thead>
        <?php include "templates/org_puesto/_tabla_filtro.php";?>
        <?php include 'templates/org_puesto/_tabla_encabezado.php';?>
        </thead>
        <tbody>
        <?php include 'templates/org_puesto/_tabla_datos.php';?>
        </tbody>
    </table>
</div>