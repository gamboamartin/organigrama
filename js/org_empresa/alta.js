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


let dp_municipio_id = -1;
let dp_cp_id = -1;
let dp_colonia_postal_id = -1;



let sl_dp_municipio_id = $("#dp_municipio_id");
let sl_dp_cp_id = $("#dp_cp_id");
let sl_dp_colonia_postal_id = $("#dp_colonia_postal_id");
let sl_dp_calle_pertenece_id = $("#dp_calle_pertenece_id");
let sl_dp_calle_pertenece_entre1_id = $("#dp_calle_pertenece_entre1_id");
let sl_dp_calle_pertenece_entre2_id = $("#dp_calle_pertenece_entre2_id");



sl_dp_estado_id.change(function(){
    dp_estado_id = $(this).val();
    let url = "index.php?seccion=dp_municipio&ws=1&accion=get_municipio&dp_estado_id="+dp_estado_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        $.each(data.registros, function( index, dp_municipio ) {
            integra_new_option("#dp_municipio_id",dp_municipio.dp_estado_descripcion+' '+dp_municipio.dp_municipio_descripcion,dp_municipio.dp_municipio_id);
        });
        sl_dp_municipio_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log("The following error occured: "+ textStatus +" "+ errorThrown);
    });
});


sl_dp_municipio_id.change(function(){
    dp_municipio_id = $(this).val();
    let url = "index.php?seccion=dp_cp&ws=1&accion=get_cp&dp_municipio_id="+dp_municipio_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        $.each(data.registros, function( index, dp_cp ) {
            integra_new_option("#dp_cp_id",dp_cp.dp_municipio_descripcion+' '+dp_cp.dp_cp_descripcion,dp_cp.dp_cp_id);
        });
        sl_dp_cp_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
    });
});

sl_dp_cp_id.change(function(){
    dp_cp_id = $(this).val();
    let url = "index.php?seccion=dp_colonia_postal&ws=1&accion=get_colonia_postal&dp_cp_id="+dp_cp_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        $.each(data.registros, function( index, dp_colonia_postal ) {
            integra_new_option("#dp_colonia_postal_id",dp_colonia_postal.dp_colonia_descripcion+' '+dp_colonia_postal.dp_cp_descripcion,dp_colonia_postal.dp_colonia_postal_id);
        });
        sl_dp_colonia_postal_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log(url);
    });
});

sl_dp_colonia_postal_id.change(function(){
    dp_colonia_postal_id = $(this).val();
    let url = "index.php?seccion=dp_calle_pertenece&ws=1&accion=get_calle_pertenece&dp_colonia_postal_id="+dp_colonia_postal_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        $.each(data.registros, function( index, dp_calle_pertenece ) {
            integra_new_option("#dp_calle_pertenece_id",dp_calle_pertenece.dp_colonia_descripcion+' '+dp_calle_pertenece.dp_cp_descripcion+' '+dp_calle_pertenece.dp_calle_descripcion,dp_calle_pertenece.dp_calle_pertenece_id);
            integra_new_option("#dp_calle_pertenece_entre1_id",dp_calle_pertenece.dp_colonia_descripcion+' '+dp_calle_pertenece.dp_cp_descripcion+' '+dp_calle_pertenece.dp_calle_descripcion,dp_calle_pertenece.dp_calle_pertenece_id);
            integra_new_option("#dp_calle_pertenece_entre2_id",dp_calle_pertenece.dp_colonia_descripcion+' '+dp_calle_pertenece.dp_cp_descripcion+' '+dp_calle_pertenece.dp_calle_descripcion,dp_calle_pertenece.dp_calle_pertenece_id);
        });
        sl_dp_calle_pertenece_id.selectpicker('refresh');
        sl_dp_calle_pertenece_entre1_id.selectpicker('refresh');
        sl_dp_calle_pertenece_entre2_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log(url);
    });
});

let fecha_inicio_operaciones = $("#fecha_inicio_operaciones");
let fecha_ultimo_cambio_sat = $("#fecha_ultimo_cambio_sat");

fecha_inicio_operaciones.change(function () {
    fecha_ultimo_cambio_sat.val(fecha_inicio_operaciones.val());
});