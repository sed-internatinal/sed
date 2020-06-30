$(function () {
    listado_productos();
    clickColores();
    agregar();
    departamentoChange();
    //getCiudadesDept();
    // getCostoEnvio();
    hideUser();
    activeTalla();
    formEnvio();
    rotColores();
    rotTallas();
    volver();

    $( ".categoria" ).click(function() {

        var estado = $(this).attr('data-despliega');
        var ico = $(this).find('i');

        console.log('estado' + estado);

        if(estado == 0){
            ico.removeClass('fa-caret-down');
            ico.addClass('fa-caret-up');
            $(this).attr('data-despliega', 1);
        }else {
            ico.removeClass('fa-caret-up');
            ico.addClass('fa-caret-down');
            $(this).attr('data-despliega', 0);
        }

        $(this).next('.sub').slideToggle( "fast", function() {
            // Animation complete.
        });
    });
    var ch= false;
    $(".filtro_a input").click(function (e){
        ch= true;
        console.log("default");
    });

    $(".filtro_a").click(function (){

        console.log($(this).find("input").prop("checked"));
        if(!ch) {
            if ($(this).find("input").prop("checked")) {
                $(this).find("input").prop("checked", false);
            } else {
                $(this).find("input").prop("checked", true);
            }
        }
        ch= false;

    });

    $('.filter li ul li a').closest('.padre_categoria').removeClass('padre_activo');
    $('.filter li ul li a.active').closest('.padre_categoria').addClass('padre_activo');


    $('.sub a ').click(function () {
        if($(this).hasClass('active'))
            $(this).removeClass('active')
        else
            $(this).addClass('active')

        $('.filter li ul li a').closest('.padre_categoria').removeClass('padre_activo');
        $('.filter li ul li a.active').closest('.padre_categoria').addClass('padre_activo');
        $cad = '';
        filtrar();
    });


});

function orderBy(){
    var order = $("#orderFiltro").val();
    var type = $("#orderFiltro").find(":selected").data("type");
    var search = document.location.search;
    search = insertParam("order",order,search);
    search = insertParam("ordertype",type,search);
    document.location.search = search;
}

function insertParam(key, value,search)
{
    key = encodeURI(key); value = encodeURI(value);

    var kvp = search.split('&');

    var i=kvp.length; var x; while(i--)
{
    x = kvp[i].split('=');

    if (x[0]==key)
    {
        x[1] = value;
        kvp[i] = x.join('=');
        break;
    }
}

    if(i<0) {kvp[kvp.length] = [key,value].join('=');}

    //this will reload the page, it's likely better to store this until finished
    return kvp.join('&');
}

function limpiarfiltros(){
    console.log("hioii");
    $('.padre_categoria').each(function () {
        $(this).find('.active').each(function () {
            $(this).find("input").click();
        });

    });
    console.log("listo");
    //filtrar();
}
function formEnvio(){
    $('#comprar').click(function (e) {
        e.preventDefault();
        $('#form_envio').submit();
    });
}

function clickColores(){
    $('.colors a').click(function(){
        var check = $(this).find('input');
        if (check.is(':checked')) {
            check.prop('checked', false);
            $(this).removeClass('active');
        }else {
            check.prop('checked', true);
            $(this).addClass('active');
        }
        filtrar();
    });
}

function listado_productos(){
    var inputs = $('.filter input');
    inputs.click(function () {
        filtrar();
    });
}
function filtrar() {
    var arrs = [], arrs_class = [];
    $('.padre_categoria').each(function () {
        arrs.push($(this).find('.active'));
    });
    $.each( arrs, function( index, value ){
        var arr_temp = $.map( value, function( el ) {
            if($(el).data('class') != null)
                return ( '.'+$(el).data('class') );
            else
                return '';
        });
        arrs_class.push(arr_temp);
    });
    var cp = [];
    for(var i = 0; i < arrs_class.length - 1; i++){
        if(i == 0)
            cp = cartesianProductOf(arrs_class[i],arrs_class[i+1]);
        else
            cp = cartesianProductOf(cp,arrs_class[i+1]);
    }
    cp = cp.join( "," );
    $('.list .item').hide();
    console.log(cp);
    if(cp && cp != ',')
        $(cp).show();
    else {
        $('.list .item').show();
    }
}

function cartesianProductOf(arr1,arr2) {
    var customerDebtorMatrix = [];
    for (var i = 0; i < arr1.length; i++) {
        for (var l = 0; l < arr2.length; l++) {
            customerDebtorMatrix.push(arr1[i]+arr2[l]);
        }
    }
    if(arr1.length == 0)
        return arr2;
    if(arr2.length == 0)
        return arr1;
    return customerDebtorMatrix;
};
var elim_id, elim_cant, elim_id_talla,parte;
function agregar() {
    $('.eliminar').click(function (e) {
        elim_id = $(this).data('id');
        elim_cant = $(this).data('cant');
        elim_id_talla = $(this).data('id-talla');
        e.preventDefault();

        $.LoadingOverlay("show",{ zIndex: 9999, image: '/js/jquery-loading-overlay/src/loading.gif'});
        addCarritoTalla($(this).data('id'),$(this).data('cant'),$(this).data('id-talla'));

    });
    $('.add_talla').click(function(e){
        e.preventDefault();
        $.LoadingOverlay("show",{ zIndex: 9999, image: '/js/jquery-loading-overlay/src/loading.gif'});
        addCarritoTalla($(this).data('id'),$('#cantidad').val(),$('#select-talla').val());
    })
    $('.select-cant').change(function () {
        $.LoadingOverlay("show",{ zIndex: 9999, image: '/js/jquery-loading-overlay/src/loading.gif'});
        setCarritoTalla($(this).parent().parent().data('id'),$(this).val(),$(this).parent().parent().data('id-talla'));
    });

    $('.eliminar_bono').click(function (e) {
        e.preventDefault();
        removeCarritoBono($(this).data('id'));
    });
}

$(document).on('confirmation', '.remodal', function () {
    console.log('Confirmation button is clicked');
    $.LoadingOverlay("show",{ zIndex: 9999, image: '/js/jquery-loading-overlay/src/loading.gif'});
    addCarritoTalla(elim_id,elim_cant,elim_id_talla);
});

function addCarrito(id,cant){
    $.ajax({
        url: $raiz+"/add-carrito/"+id+"/"+cant,
    })
        .done(function(data) {
            console.log(data.cantidad);
            $('#cantidad_'+id).html(data.cantidad);
            verGocarrito();
            if(window.location.href.indexOf("datos") > -1) {
                /*
                 if(data.cantidad < 1){
                 $('#fila_'+id).remove();
                 }
                 */
                window.location.reload();
            }
        })
        .fail(function() {
        })
        .always(function() {
        });

    function verGocarrito(){
        TweenMax.to($('.go_carrito'), 0.8, { 'opacity': 1 });
    }
}

function addCarritoTalla(id,cant,talla){

    $.ajax({
        url: $raiz+"/add-carrito-talla/"+id+"/"+cant+"/"+talla,
    })
        .done(function(data) {
            console.log(data.cantidad);
            //window.location = data.ruta;

             if(window.location.href.indexOf("carrito-de-compras") > -1) {
             window.location.reload();
             }
             window.location = $raiz + '/carrito-de-compras';

        })
        .fail(function() {
            $.LoadingOverlay("hide");
        })
        .always(function() {
        });
}

function removeCarritoBono(id){

    $.ajax({
        url: $raiz+"/remove-carrito-bono/"+id,
    })
        .done(function(data) {
            console.log(data.cantidad);
            if(window.location.href.indexOf("carrito-de-compras") > -1) {
                window.location.reload();
            }
            window.location = $raiz + '/carrito-de-compras';
        })
        .fail(function() {
            $.LoadingOverlay("hide");
        })
        .always(function() {
        });
}

function setCarritoTalla(id,cant,talla){

    $.ajax({
        url: $raiz+"/set-carrito-talla/"+id+"/"+cant+"/"+talla,
    })
        .done(function(data) {
            console.log(data.cantidad);
            if(window.location.href.indexOf("carrito-de-compras") > -1) {
                window.location.reload();
            }
            window.location = $raiz + '/carrito-de-compras';
        })
        .fail(function() {
            $.LoadingOverlay("hide");
        })
        .always(function() {
        });
}

/****  Dirección ***/

function departamentoChange(){
    if($.isNumeric($('#envio_ciudad').val())){
        getCiudadesDept(false);
    }
    $('#envio_departamento').change(function () {
        getCiudadesDept(true);
    });
    $('#envio_ciudad').change(function () {
        getCostoEnvioTCC(true);
    });
}
function getCiudadesDept(loader){
    if (window.location.href.indexOf("carrito-de-compras") > -1) {
        if (loader)
            $.LoadingOverlay("show", {zIndex: 9999, image: '/js/jquery-loading-overlay/src/loading.gif'});
        $.ajax({
            url: $raiz + "/ciudades-dept/" + $('#envio_departamento').val() + "/" + $('#envio_ciudad').val(),
        }).done(function (html) {
            $('#envio_ciudad').html(html);
            getCostoEnvioTCC(true);
        }).fail(function () {
            console.log('ERROR GETTING CITIES INFORMATION BY STATE')
        }).always(function () {
            $.LoadingOverlay("hide");
        });
    }
}

function getCostoEnvioTCC(loader) {
    $('#costo-envio-span').html('');
    $('#costo-envio').val('');
    if (loader){
        $.LoadingOverlay("show", {zIndex: 9999, image: '/js/jquery-loading-overlay/src/loading.gif'});
    }
    $.ajax({
        url: $raiz + "/get-costo-envio-tcc/" + $('#envio_ciudad').val() + "/",
    }).done(function (data) {
        costoEnvio(data);
    }).always(function () {
        $.LoadingOverlay("hide");
    });
}

function costoEnvio(data) {
    $('#costo-envio-span').html("$" + addCommas(data.total_despacho));
    $('#costo-envio').val(data.total_despacho);
    var costo_envio = parseInt(data.total_despacho);
    var total_iva = parseFloat($("#carrito-iva_").data("total-iva")) + parseFloat(data.total_despacho_mas_iva) - parseFloat(data.total_despacho)
    $('#carrito-iva_').html("$" + addCommas(total_iva))
    var result = parseInt($('#total-carrito').val()) + parseFloat(data.total_despacho_mas_iva);
    $('#total-resultado').html("$" + addCommas(result))
}

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function hideUser(){
    $('#envio_user').parent().hide();
}

function activeTalla(){
    $('.size a').click(function(){
        var value = $(this).data('value');
        $('#select-talla').val(value);
        $('.size a').removeClass('active');
        $(this).addClass('active');
    });
}

function rotColores() {
    $('.colores img').click(function () {
        $pos = $(this).index();
        $padre = $(this).closest('.item').find('.addserv');
        $padre.find('.addCarrito').hide();
        $padre.find('.addCarrito').eq($pos).css('display','inline-block');

        $padreColor = $(this).parent();
        $padreColor.find('img').removeClass('activa');
        $(this).addClass('activa');
        console.log($pos);

    });
}
function rotTallas() {
    $('.sel_talla').change(function () {
        $val = $(this).val();
        console.log($val);
        $(this).next().find('a').hide();
        $(this).next().find('.talla_'+$val).css('display','inline-block');
    });
}
function volver(){
    $('.volver').click(function (e) {
        e.preventDefault();
        $('#boton_parte').attr('href',$(this).attr('href'));
        var inst = $('[data-remodal-id=modalVolver]').remodal();
        inst.open();

    })
}