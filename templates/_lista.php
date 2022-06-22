<?php
/** @var controllers\controlador_org_puesto $controlador */
?>
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
        <?php //include 'templates/org_puesto/_tabla_datos.php';?>

        <?php
        foreach ($controlador->registros as $registro){
            echo "<tr>
                        <td>$registro[org_puesto_id]</td>
                        <td>$registro[org_puesto_descripcion]</td>
                        <td>$registro[org_puesto_codigo]</td>
                        <td>$registro[org_puesto_status]</td>
                        <td>$registro[org_puesto_org_empresa_id]</td>
                        <td style='display: table-cell;'>
                            <a href='#' class='btn btn-info'>
                                <i class='icon-edit'></i> Edit</a>
                        </td>
                        <td style='display: table-cell;'>
                            <a href='#' onclick='return confirm('Are you sure?')' class='btn btn-danger'>
                                <i class='icon-remove'></i> Delete</a>
                        </td>
                    </tr>";
        } ?>

        </tbody>
    </table>
</div>