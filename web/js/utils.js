$raiz = '';
if(window.location.href.indexOf("app_dev.php") > -1) {
    $raiz = '/app_dev.php';
}


function filtrarDepto(elem){
    $(".nopersonas").hide();
    var id= elem.value;
    console.log(id);
    $(".userDeptos").hide();
    $(".depto_"+id).show();
    if(id==0)
        $(".userDeptos").show();
    if($(".depto_"+id).length == 0 && id != 0)
        $(".nopersonas").show();
}

function enviarformulario(){
    $("#buscador_directorio").submit();
}

var form_news;
function newsletter(){
    $('#form_newsletter').submit(function (e) {
        $.LoadingOverlay("show",{ zIndex: 9999, image: '/js/jquery-loading-overlay/src/loading.gif'});
        e.preventDefault();
        $('#terms_error,#news_email_error').hide();
        $acepto = $('#acepta').prop('checked');
        if($acepto){
            if(validateEmail($(this).find('input[type="text"]').val())){
                data = $(this).serialize();
                $.ajax({
                    url: $raiz+"/newsletter/"+$(this).find('input[type="text"]').val()
                }).done(function(data) {
                    $.LoadingOverlay("hide");
                    $('#exitoso').remove();
                    if(data.success == 1 || data.success == "1"){
                        $('#form_newsletter').prepend('<p id="exitoso">Inscrito exitosamente</p>');
                        $('#form_newsletter').find('input[type="text"]').val('');
                    }else{
                        $('#form_newsletter').prepend('<p id="exitoso" class="error">Email inscrito anteriormente</p>');
                    }
                });
            }else {
                $.LoadingOverlay("hide");
                $('#news_email_error').css('display','inline-block');
            }
        }else{
            $.LoadingOverlay("hide");
            $('#terms_error').css('display','inline-block');
        }
    });
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

$(function(){

    $('#hamburguesa').click(function () {
        $('nav').slideToggle();
    })

    $("#fos_user_registration_form_email").change(function(){
        console.log("cambio");
       $("#fos_user_registration_form_username").val($(this).val());
    });

    $("#app_user_registration_email").change(function(){
        console.log("cambio");
        $("#app_user_registration_username").val($(this).val());
    });

    $(".share").jsSocials({
        shares: [
            {share: "email", logo: 'fas fa-envelope' },
            {share: "twitter", logo: 'fab fa-twitter' },
            {share: "facebook", logo: 'fab fa-facebook' },
            {share: "googleplus", logo: 'fab fa-google-plus-g' },
            {share: "linkedin", logo: 'fab fa-linkedin' }]
    });

    newsletter();

    //Slider Main | Home
    var swiper1 = new Swiper('.home_swiper',{
        loop: true,
        speed: 1200,
        autoplay: {
            delay: 10000
        },
        clickable: true,
        allowTouchMove: false,
        on: {
            init: function () {
                $('.swiper-slide-active').find('.pics_slide').addClass('visible');
                animaPrbanner();
            },
            transitionStart: function () {

                console.log(this.activeIndex);
                if(this.previousIndex != this.activeIndex){
                    restarPosition();
                }
            },
            transitionEnd: function () {
                console.log('fin cambio');

                $('.swiper-slide').find('.pics_slide').removeClass('visible');
                $('.swiper-slide-active').find('.pics_slide').addClass('visible');
                animaPrbanner();
            }
        }
    });

    function animaPrbanner(){
        TweenMax.to($('.visible div:nth-of-type(1) img'), 3, { css:{'margin-left': 0}, ease: Elastic.easeOut.config(1, 0.75), delay: 1 });
        TweenMax.to($('.visible div:nth-of-type(2) img'), 3, { css:{'margin-left': 0}, ease: Elastic.easeOut.config(1, 0.75), delay: 1 });

        console.log('anima');
    }

    function restarPosition() {
        TweenMax.to($('.swiper-slide .pics_slide div:nth-of-type(1) img'), 1, { css:{'margin-left': '-150%'}, delay: 1 });
        TweenMax.to($('.swiper-slide .pics_slide div:nth-of-type(2) img'), 1, { css:{'margin-left': '150%'}, delay: 1 });
    }

    //Slider Marcas | Home
    var swiper2 = new Swiper('.marcas_swiper',{
        navigation: {
            nextEl: '.cont_marcas .next',
            prevEl: '.cont_marcas .prev',
        },
        speed: 800,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        loop: true,
        loopFillGroupWithBlank: true,
        spaceBetween: 30,
        paginationClickable: false,
        slidesPerView: 5,
        breakpoints: {
            720: {
                slidesPerView: 3
            },
            600: {
                slidesPerView: 2
            }
        }
    });
    //Slider Productos | Home
    var swiper3 = new Swiper('.productos_swiper',{
        navigation: {
            nextEl: '.content_products .next',
            prevEl: '.content_products .prev',
        },
        // loop: true,
        speed: 800,
        paginationClickable: false,
        slidesPerView: 4,
        breakpoints: {
            1360: {
                slidesPerView: 3
            },
            800: {
                slidesPerView: 2
            },
            720: {
                slidesPerView: 1
            }
        }
    });
    //Slider Promociones | Home
    var swiper4 = new Swiper('.promos_swiper',{
        navigation: {
            nextEl: '.content_promos .next',
            prevEl: '.content_promos .prev',
        },
        loop: true,
        speed: 800,
        paginationClickable: false,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        slidesPerView: 3,
        breakpoints: {
            720: {
                slidesPerView: 3
            },
            600: {
                slidesPerView: 1
            }
        }
    });
    //Slider Promociones | Home
    var swiper4 = new Swiper('.swiper-promos-mob',{
        navigation: {
            nextEl: '.swiper-promos-mob .next',
            prevEl: '.swiper-promos-mob .prev',
        },
        loop: true,
        speed: 800,
        paginationClickable: false,
        slidesPerView: 3,
        breakpoints: {
            720: {
                slidesPerView: 3
            },
            600: {
                slidesPerView: 1
            }
        }
    });
    //Slider Promociones
    var swiper5 = new Swiper('.promociones_swiper_main', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows : false
        },
        navigation: {
            nextEl: '.nav_bar_promos .promo_next',
            prevEl: '.nav_bar_promos .promo_prev'
        }
    });
    //Slider Servicios Home | Unidad_1
    var swiper6 = new Swiper('.sw_unidad_1', {
        speed: 1200,
        // allowTouchMove: false,
        pagination: {
            el: '.pagnt_unidad_1',
            clickable: true
        }
    });
    //Slider Servicios Home | Unidad_2
    var swiper7 = new Swiper('.sw_unidad_2', {
        speed: 1200,
        // allowTouchMove: false,
        pagination: {
            el: '.pagnt_unidad_2',
            clickable: true
        }
    });
    //Slider Servicios Home | Unidad_3
    var swiper8 = new Swiper('.sw_unidad_3', {
        speed: 1200,
        // allowTouchMove: false,
        pagination: {
            el: '.pagnt_unidad_3',
            clickable: true
        }
    });
    


    //Slider Productos Relacionados
    var swiper11 = new Swiper('.productos_relacionados', {
        speed: 1200,
        slidesPerView: 4,
        spaceBetween: 0,
        breakpoints: {
            1456: {
                slidesPerView: 3
            },
            1120:{
                slidesPerView: 2,
                spaceBetween: 0
            },
            720:{
                slidesPerView: 1
            }
        }
    });



    //Menú Mobile
    var slideout = new Slideout({
        'panel': document.getElementById('panel'),
        'menu': document.getElementById('menu'),
        'padding': 256,
        'tolerance': 70
    });
    document.querySelector('.toggle-button').addEventListener('click', function() {
        slideout.toggle();
    });

    //Scrolldown
    $('a[href*="#"]')
        .not('[href="#"]')
        .not('[href="#0"]')
        .click(function(event) {
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                &&
                location.hostname == this.hostname
            ) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 2500, function() {
                        var $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) {
                            return false;
                        } else {
                            $target.attr('tabindex','-1');
                            $target.focus();
                        };
                    });
                }
            }
    });
    // End Scrolldown

    //Go Up
    $('.goup').click(function(){
        $('body, html').animate({
            scrollTop: '0px'
        }, 850);
    });
    $(window).scroll(function(){
        if( $(this).scrollTop() > 320 ){
            $('.goup').css("transform","translateY(0px)");
        } else {
            $('.goup').css("transform","translateY(200px)");
        }
    });


    //Slow scroll
    // if (window.addEventListener) window.addEventListener('DOMMouseScroll', wheel, false);
    // window.onmousewheel = document.onmousewheel = wheel;
    // function wheel(event) {
    //     var delta = 0;
    //     if (event.wheelDelta) delta = event.wheelDelta / 120;
    //     else if (event.detail) delta = -event.detail / 3;
    //
    //     handle(delta);
    //     if (event.preventDefault) event.preventDefault();
    //     event.returnValue = false;
    // }
    // function handle(delta) {
    //     //Tiempo
    //     var time = 1000;
    //     //Distacia a recorrer
    //     var distance = 500;
    //
    //     $('html, body').stop().animate({
    //         scrollTop: $(window).scrollTop() - (distance * delta)
    //     }, time );
    // }

    $(".country").click(function () {
        $(".sub_list").slideToggle();
    });

    $(".login_btn").click(function () {
        $(".login").fadeToggle();
    });

    $(".open_login").click(function () {
        $(".login").fadeToggle();
    });

    $(".btn_ficha").click(function () {
        $(".ficha_t").slideToggle();
    });
    $(".btn_garantia").click(function () {
        $(".garant").slideToggle();
    });

    $(function () {

        $('.scroll-section').scrollIndicatorBullets({
            titleSelector: 'b'
        });
    });

    /*$('.cont_title_big_contact').flowtype({
        fontRatio : 9
    });
    $('.cont_title_small_contact').flowtype({
        fontRatio : 9
    });*/

    var showcase = $("#carouselC9");
    if (!isMobile()){
        showcase.Cloud9Carousel( {
            mirror: {
                gap: 12,
                height: 0.8
            },
            buttonLeft: $(".promociones .prev"),
            buttonRight: $(".promociones .next"),
            autoPlay: 1,
            autoPlayDelay: 3000,
            speed: 1,
            bringToFront: true,
            yOrigin: 42,
            yRadius: 48,
            xRadius: 350,
            onLoaded: function() {
                showcase.css( 'visibility', 'visible' )
                showcase.css( 'display', 'none' )
                showcase.fadeIn( 1500 )
            }
        } );
    }

});

function RandRange(min, max){
    return Math.floor(Math.random() * (max - min) + min);
}

$( document ).ready(function() {

    var section = $('li');
    if(!isMobile() && !isIpad()){
        function toggleAccordion() {
            section.removeClass('active');
            $(this).addClass('active');
        }

        section.on('click', toggleAccordion);
    }

    registro_width();
    height_servicios();
    height_programas();
    hoverOfertas();
});


$( window ).resize(function() {
    setTimeout(function(){
        var var1 = $('.wrap_servicios .img');
        var var2 = $('.wrap_servicios .txt').height();

        var1.css('height', var2 );
    },500);

    registro_width();
    height_servicios();
    height_programas();
});


$(window).on("load", function() {
    registro_width();

});

$(window).scroll(function(){

});

function hoverOfertas() {
    $( ".oferta" ).hover(
        function() {
            var hoverDisplay = $(this).find('.oferta_desc');
            var back = $(this).find('.back');
            var percent = $(this).find('.descuento');
            var txt = $(this).find('.text');
            TweenMax.to(hoverDisplay, 0.5, {opacity: 1});
            TweenMax.to(txt, 0.5, {left: '5%'});
            TweenMax.to(back, 0.3, {width: '400%', height: '400%', left: '-100%', top:'-100%'});
            TweenMax.to(percent, 0.3, {opacity: 1, right: '0'});
        }, function() {
            var hoverDisplay = $(this).find('.oferta_desc');
            var back = $(this).find('.back');
            var percent = $(this).find('.descuento');
            var txt = $(this).find('.text');
            TweenMax.to(hoverDisplay, 0.5, {opacity: 0});
            TweenMax.to(back, 0.5, {width: '200%', height: '200%', left: '0%', top:'0%'});
            TweenMax.to(percent, 0.5, {opacity: 0, right: '-10%'});
            TweenMax.to(txt, 0.5, {left: '10%'});
        }
    );
}


function isMobile() {
    return(/Android|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) );
}

function isIpad() {
    return navigator.userAgent.match(/iPad/i);
}

function registro_width() {
    var wDocument = $( document ).width();
    /*
    if( wDocument < 1024 ) {
        $('.cont_title_big').find('.no_mrg').flowtype({
            fontRatio : 5
        });
        // console.log("Menor");
    }else if(wDocument > 1024){
        $('.cont_title_big').flowtype({
            fontRatio : 9
        });
        $('.cont_title_small').flowtype({
            fontRatio : 9
        });
        // console.log("Mayor");
    }*/
}

function height_servicios() {
    setTimeout(function(){
        var columnaInfo = $('.unidad .description');
        var variable_1 = $('.unidad .slider').height();
        columnaInfo.css('height', variable_1 );
    },500);
}

function height_programas() {
    setTimeout(function(){
        var columnaInfo = $('.programas .txt_hg');
        var variable_1 = $('.programas .pic_hg').height();
        columnaInfo.css('height', variable_1 );
    },500);
}
