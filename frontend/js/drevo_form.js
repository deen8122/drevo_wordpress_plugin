function drevo_form_show(id,event_title) {
    $layer = $('#df_layer_' + id);
    $container = $('#df_container_' + id);
    $layer.fadeIn(300);
    $container.fadeIn(500);
    $container.find('.event_title').val(event_title);
     $container.find('.drevo-form-title').html(event_title);

}


function drev_form_hide(id) {
    $layer = $('#df_layer_' + id);
    $container = $('#df_container_' + id);
    $layer.fadeOut(200);
    $container.fadeOut(400);

}
jQuery(function ($) {
    $(".drevo-form").submit(function () {
        var data = $(this).serialize();
        var id = $(this).data('id');
        //alert(id);
            $.ajax({
                type: "POST",
                url: "/",
                data: data,
                success: function (msg) {
                    console.log(msg);
                    var n = msg.search("ok");
                    console.log(n);
                    $('.drevo-form-form'+id).hide(100);
                    if(n>0){
                        
                         $('.drevo-form-success'+id).show(100);
                        //drevo-form-success
                    }else{
                        $('.drevo-form-error'+id).show(100);
                    }
                    
                }
            });
        
        console.log(data);
        return false;
    });
});

