/*
 * Все JS функции плагина ДРЕВО "УПРАВЛЕНИЕ ДАННЫМИ"
 * Версия 1.0.0
 * Разработка 2Dweb.ru
 * 
 */
function _open(url, width, height) {
    window.open(url, '', 'width=' + width + ',height=' + height + ',left=' + ((window.innerWidth - width) / 2) + ',top=' + ((window.innerHeight - height) / 2));
}
/*
jQuery.datepicker.setDefaults({
    changeMonth: true,
    changeYear: true,
    closeText: 'Закрыть',
    prevText: '&#x3c;Пред',
    nextText: 'След&#x3e;',
    currentText: 'Сегодня',
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
        'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
        'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
    dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
    dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false
});

jQuery(function () {
    window.setInterval(function () {
        jQuery.get("../session.php");
    }, 1000 * 60);
});
*/

function word(selector) {// if(test!=1) alert(selector);
    /*
     if(CSSPATH==""){
     var csspath = "./engine/data/system_skin/style1.css";
     } else {
     var csspath = CSSPATH;
     }/**/
    //var csspath = "../engine/data/system_skin/style1.css";


    jQuery(selector).tinymce({
        // Location of TinyMCE script
        //script_url: ajaxurl+'?pg=ajax&action=wp_ajax&js/tiny_mce/tiny_mce.js',
        language: 'ru',
        relative_urls: false,
        convert_urls: false,
        remove_script_host: false,
        remove_linebreaks: true,
        // General options
        theme: "advanced",
        //plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        //plugins : "safari,pagebreak,advhr,advimage,advlink,emotions,iespell,inlinepopups,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,table",
        plugins: "safari,pagebreak,advhr,advimage,advlink,iespell,inlinepopups,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,table",
        // Theme options
        theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,|,hr,removeformat,|,fullscreen",
        theme_advanced_buttons2: "pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,leanup,charmap,|,tablecontrols,|,code",
        theme_advanced_buttons3: "",
        theme_advanced_buttons4: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        extended_valid_elements: "img1,img2,img3,img4,img5,img6,img7,img8,img9,img10,img11,img12,img13,img14,img15,img16,img17,img18,img19,img20,img21,img22,img23,img24,img25,img26,img27,img28,img29,img30,img31,img32,img33,img34,img35,img36,img37,img38,img39,script[type|language|src]",
        theme_advanced_resizing: true

                // Example content CSS (should be your site CSS)
                //content_css : csspath
    });
    jQuery(selector).addClass("tinymce");
}

jQuery(function () {
    /*if(allword){
     word('textarea',1);
     } else {
     word('textarea.word',1);
     }*/

    jQuery(".list").addClass("odd");
    jQuery(".list tr").addClass("oddnob");
   jQuery(".list tr:nth-child(odd)").addClass("odd");

    /*
     setTimeout(function(){
     jQuery("textarea").each(function(){
     var ths = jQuery(this);
     if(!ths.hasClass("tinymce")){
     if(ths.attr("id")==""){
     ths.attr("id",ths.attr("name"));
     }
     ths.after("<a class='tinymce' href='' rel='"+ths.attr("id")+"'>x</a>");
     ths.parent().children("a.tinymce").click(function(){
     word('#'+ths.attr("id"));
     ths.parent().children("a.tinymce").hide();
     return false;
     });
     }
     });
     },3000);*/

    jQuery('textarea').each(function () {
        var textarea = jQuery(this);
        if (textarea.attr("id") == "") {
            textarea.attr("id", textarea.attr("name"));
        }

        textarea.after("<div><a href='#' id='" + textarea.attr("id") + "_ex'><small>Включить расширенный редактор</small></a></div>");
        jQuery("#" + textarea.attr("id") + "_ex").click(function () {
            word("#" + textarea.attr("id"));
            jQuery(this).parent().fadeOut(function () {
                jQuery(this).html("<a href=\"javascript:;\" onmousedown=\"\jQuery('#" + textarea.attr("id") + "').tinymce().hide();\jQuery(this).fadeOut();\">Убрать редактор</a> ");
                jQuery(this).fadeIn();
            });
        });
    });




    window.setInterval(function () {
        jQuery('a#anews').fadeOut().fadeIn()
    }, 2000);
    jQuery('a#anews').click(function () {
        jQuery("body").append("<div id='bnews' style='display:none'></div>");
        var lnk = jQuery(this);

        jQuery.get(jQuery(this).attr("href"), function (data) {
            jQuery('#bnews').html(data);

            var ttl = jQuery('#bnews').children("h2").html();
            jQuery('#bnews').children("h2").html("").remove();

            jQuery('#bnews').dialog({
                title: ttl,
                bgiframe: true, width: 450, height: 300, modal: true,
                overlay: {backgroundColor: '#000', opacity: 0.8},
                buttons: {
                    'OK': function () {
                        jQuery(this).dialog('close');
                    },
                    'Прочитал, больше не показывать': function () {
                        jQuery.get(lnk.attr("href") + "&readed", function () {
                            var txt = jQuery('a#anews').html();
                            jQuery('a#anews')
                                    .html(txt.substr(0, txt.length - 3))
                                    .css({"color": "white", "font-weight": "100"})
                                    .attr("id", "");
                        });
                        jQuery(this).dialog('close');
                    }
                },
                close: function () {
                    jQuery(this).dialog('destroy');
                }
            });
        });

        return false;
    });


});

