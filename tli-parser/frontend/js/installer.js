$(document).ready(function(){

    $('form').submit(function(event) {

        event.preventDefault();
        host = $('#host').val();
        user = $('#user').val();
        password = $('#password').val();
        name = $('#name').val();
        
        $.ajax({
            type: 'post',
            url: '/installer/check',
            dataType: 'text',
            data: 'host='+host+'&user='+user+'&password='+password+'&name='+name,
            success: function (data) {
                $('#response').html(data);
            }
        });
    });

});