$(document).ready(function() {
    init();
});

function init(){
    $('.form_date').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $(".country").click(function(){
        var country = $(this).attr('id');
        document.location.href += '/' + country;

        //    $.ajax({
        //        type: $(this).attr('method'),
        //        url: $(this).attr('action'),
        //        data: $(this).serialize()
        //    }).done(function (data) {
        //        $('#page').html(data['html']);
        //        init();
        //    }).fail(function (jqXHR, textStatus, errorThrown) {
        //        console.log(errorThrown);
        //    });
        //
        //    return false;
    });


    $(".continent").click(function(){
        var continent = $(this).attr('id');
        document.location.href = '/content/' + continent;

        //    $.ajax({
        //        type: $(this).attr('method'),
        //        url: $(this).attr('action'),
        //        data: $(this).serialize()
        //    }).done(function (data) {
        //        $('#page').html(data['html']);
        //        init();
        //    }).fail(function (jqXHR, textStatus, errorThrown) {
        //        console.log(errorThrown);
        //    });
        //
        //    return false;
        });

    $(".blogPost").click(function(){
        var blogPost = $(this).find('.info').val();
        document.location.href += '/' + blogPost;

    });
}

