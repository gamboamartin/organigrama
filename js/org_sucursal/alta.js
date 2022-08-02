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
let fecha_inicio_operaciones = '0000-00-00';


let sl_dp_pais_id = $("#dp_pais_id");
let sl_org_empresa_id = $("#org_empresa_id");

let txt_fecha_inicio_operaciones = $('#fecha_inicio_operaciones');


sl_org_empresa_id.change(function(){

    let selected = $(this).find('option:selected');

    fecha_inicio_operaciones = selected.data('org_empresa_fecha_inicio_operaciones');
    dp_pais_id = selected.data('dp_pais_id');

    if(fecha_inicio_operaciones !== '0000-00-00'){
        txt_fecha_inicio_operaciones.val(fecha_inicio_operaciones);
    }
    sl_dp_pais_id.val(dp_pais_id);
    sl_dp_pais_id.selectpicker('refresh')

});

