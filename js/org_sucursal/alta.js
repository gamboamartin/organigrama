function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    const regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function integra_new_option(container, descripcion, value){
    let new_option =new_option_sl(descripcion,value);
    $(new_option).appendTo(container);
}


function new_option_sl(descripcion,value){
    return "<option value =" + value + ">" + descripcion + "</option>";
}
let session_id = getParameterByName('session_id');
let dp_pais_id = -1;
let dp_estado_id = -1;
let dp_municipio_id = -1;
let dp_cp_id = -1;
let dp_colonia_postal_id = -1;

let sl_org_empresa_id = $("#org_empresa_id");
let txt_fecha_inicio_operaciones = $('#fecha_inicio_operaciones');

sl_org_empresa_id.change(function(){
    let selected = $(this).find('option:selected');
    let fecha_inicio_operaciones = selected.data('org_empresa_fecha_inicio_operaciones');
    if(fecha_inicio_operaciones !== '0000-00-00'){
        txt_fecha_inicio_operaciones.val(fecha_inicio_operaciones);
    }

});

