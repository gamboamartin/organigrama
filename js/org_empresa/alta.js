function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    const regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
let session_id = getParameterByName('session_id');
let dp_pais_id = -1;
let sl_dp_estado_id = $("#dp_estado_id");
let sl_dp_pais_id = $("#dp_pais_id");
sl_dp_pais_id.change(function(){
    dp_pais_id = $(this).val();

    let url = "index.php?seccion=dp_estado&ws=1&accion=get_estado&dp_pais_id="+dp_pais_id+"&session_id="+session_id;

    $.ajax({
        type: 'GET',
        url: url,
    }).done(function( data ) {  // Función que se ejecuta si todo ha ido bien
        console.log(data);
        $.each(data.registros, function( index, dp_estado ) {

            var new_option ="<option value ="+dp_estado.dp_estado_id+">"+dp_estado.dp_pais_descripcion+' '+dp_estado.dp_estado_descripcion+"</option>";
            $(new_option).appendTo("#dp_estado_id");
        });
        sl_dp_estado_id.selectpicker('refresh');
    }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
        alert('Error al ejecutar');
        console.log("The following error occured: "+ textStatus +" "+ errorThrown);
    });
});