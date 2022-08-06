function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}
let url = getAbsolutePath();
let base_js = url+'js/base.js';
let direcciones_js = url+'js/direcciones.js';
document.write('<script src="'+base_js+'"></script>');
document.write('<script src="'+direcciones_js+'"></script>');



let session_id = getParameterByName('session_id');

let fecha_inicio_operaciones = '0000-00-00';


let sl_org_empresa_id = $("#org_empresa_id");

let txt_fecha_inicio_operaciones = $('#fecha_inicio_operaciones');


sl_org_empresa_id.change(function(){

    let selected = $(this).find('option:selected');

    fecha_inicio_operaciones = selected.data('org_empresa_fecha_inicio_operaciones');
    dp_pais_id = selected.data('dp_pais_id');
    dp_estado_id = selected.data('dp_estado_id');
    dp_municipio_id = selected.data('dp_municipio_id');
    dp_cp_id = selected.data('dp_cp_id');
    dp_colonia_postal_id = selected.data('dp_colonia_postal_id');
    dp_calle_pertenece_id = selected.data('dp_calle_pertenece_id');

    if(fecha_inicio_operaciones !== '0000-00-00'){
        txt_fecha_inicio_operaciones.val(fecha_inicio_operaciones);
    }


    dp_asigna_estados(dp_pais_id,dp_estado_id);
    dp_asigna_municipios(dp_estado_id,dp_municipio_id);
    dp_asigna_cps(dp_municipio_id,dp_cp_id);
    dp_asigna_colonias_postales(dp_cp_id,dp_colonia_postal_id);
    dp_asigna_calles_pertenece(dp_colonia_postal_id,dp_calle_pertenece_id)


});

