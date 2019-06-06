$(document).ready(function() {
 function showValidationError() {
        $(this).removeClass('not_error').addClass('error');
        $('.error-box-url').css('display','block');
        $('.error-box-url').animate({'paddingTop':'5px'},400);
        $('#url').css('border','1px solid red');
        $('#submit').attr("disabled", true);
    }
    //Прелоадер
    setTimeout(function(){
        $(".loading").css('display', 'none');
        

    }, 1000);



    // Устанавливаем обработчик потери фокуса для всех полей ввода текста
    $('#url, #select, #textarea').unbind().blur( function() {
        var id = $(this).attr('id');
        var val = $(this).val();
        $('#select').on('change', function(){
            if($(this).val() == 1){
                $('#textarea, #label').show();
                $('#textarea').attr('required');
                $('#submit').css('cursor', 'default');
                $('#submit').attr("disabled", true);
                $('.indent').css('display', 'block');


            } else {

                $('#textarea, #label, .error-box-textarea').hide();
                $('#select').addClass('not_error');
                $('#submit').removeAttr('disabled');
                $('#submit').css('cursor', 'pointer');
                $('#textarea').val('');
                $('.indent').css('display', 'none');
            };

        });




        // После того, как поле потеряло фокус, перебираем значения id, совпадающие с id данного поля
        switch(id)
        {
            // Проверка поля "Адрес"
            case 'url':
                var rv_url = /\S{4,255}/; // используем регулярное выражение

                if(val != '' && rv_url.test(val)) {
                    $(this).addClass('not_error');
                    $(this).next('.error-box-url');
                    $('#url').css('border','1px solid green');
                    $('.error-box-url').css('display','none');



                    //При завершении заполнения поля адреса страницы пользователем сама страница дополнительно проверяется с помощью ajax
                    url = 'engine/checkurl\/'+$('#url').val();
                    $.ajax({

                        url: url,
                        dataType: 'text',
                        timeout: 3000,

                        //Обрабатываем ответ
                        success: function(data) {

                            //Если в ответе что-то пришло, то backend достучался до адреса
                            if(data.length > 0) {

                                //Заменить введенный адрес пользователем на конечный (в случае редиректа)
                                $('#url').val(data);
                            }
                            //Если backend не достучался до адреса, то он некорректен, и в этом случае пока отдается общее сообщение об ошибки валидации, в идеале сделать отдельное
                            else {

                                showValidationError();
                            }
                        }

                    });


                } else {

                    showValidationError();
                }
                break;

            // Проверка Вида парсинга
            case 'select':

                if(val != '' && ('url').val != '')
                {
                    $(this).addClass('not_error');
                    $(this).next('.error-box-select');
                    $('#select').css('border','1px solid green');
                    $('.error-box-select').css('display','none');
                }


                else
                {
                    $(this).removeClass('not_error').addClass('error');
                    $('#select').css('border','1px solid red');
                    $('.error-box-select').css('display','block');
                    $('.error-box-select').animate({'paddingTop':'5px'},400);


                }
                break;

            // Проверка поля "Комментарий"
            case 'textarea':

                var rv_textarea = /\S{2,255}/;
                if(val != '' && rv_textarea.test(val)) {

                    $(this).addClass('not_error');
                    $(this).next('.error-box-textarea');
                    $('#textarea').css('border','1px solid green');
                    $('.error-box-textarea').css('display','none');

                } else {
                    $(this).removeClass('not_error').addClass('error');
                    $('#textarea').css('border','1px solid red');
                    $('.error-box-textarea').css('display','block');
                    $('.error-box-textarea').animate({'paddingTop':'5px'},400);

                }

                break;
        }

        var textChars = document.getElementById('textarea');
        $(textChars).keyup(function() {
            if(textChars.value.length >= 2) {
                $('#textarea').addClass('not_error');
                $('#submit').css('cursor', 'pointer');
                $('#submit').removeAttr('disabled');


            } else {

                $('#textarea').removeClass('not_error');
                $('#submit').css('cursor', 'default');
                $('#submit').attr("disabled", true);

            }
        })
$('#submit').click(function(){
 if($('#select').children(':selected').val() == 1) {
     $('#submit').attr("disabled", true);
     $('#submit').css('cursor', 'default');
 }


        if($('#select').hasClass('not_error'),
            $('#url').hasClass('not_error'),
            $('#textarea').hasClass('not_error')) {
            $('#submit').removeAttr("disabled").css('cursor', 'pointer');
        }
})





    });


        $('form').submit(function(event) {

            event.preventDefault();

            $('#submit').attr("disabled", true);
            $('#data-count').html('Загрузка результатов...');
            $('#data-form').html('');

            //Собираем динамические части url для Ajax
            textSource = $('#textarea').val();
            textReplaced = textSource+'\/';

            //Если парсится не текст, то опустошаем переменную отвечающую за передаваемый в url текст
            if (textSource.length == 0) {

                textReplaced = '';
            }

            select = $('#select').children(':selected').attr('id');

            //Конкатенируем статическую и динамическую часть
            url ='engine/ajax/'+select+'\/'+textReplaced+$('#url').val();

            //Выполняем Ajax на собранный url
            $.ajax({
                type: 'get',
                url: url,
                dataType: 'json',
                timeout: 120000,

                //Обрабатываем ответ
                success: function(data) {

                    //Проходимся по полученному JSON и собираем весь ответ в одну строку, проставляя нужные теги, атрибуты в зависимости от вида парсинга
                    dataString = '';


                    $.each(data, function(index, item) {

                        if (select == 'text') {

                            dataTag = 'p';
                            dataAttr = '';
                        }

                       if (select == 'images') {

                            dataTag = 'img';
                            dataAttr = ' src=\''+data[index]+'\'';
                            data[index] = '';

                        }

                       if (select == 'links') {

                            dataTag = 'a';
                            dataAttr = ' href=\''+data[index]+'\''+'target='+'_blank';
                            //добавила открытие в новой вкладке

                        }

                        //Строка готова, скормим ее в блок #data-form
                        dataString = dataString+'<'+dataTag+dataAttr+'>'+data[index]+'<\/'+dataTag+'>'+'<br>';
                        $('#data-form').html(dataString);
                        $('#data-count').html('Найдено результатов: '+index);
                        $('#url').val('');
                        $('#select').val('');
                        $('#textarea').val('');
                        $('#textarea, #label').hide();

                    });


                    


                    if(($('#data-form').html().length) == 0) {

                        $('#data-count').html('Результатов не найдено');
                    }

                    $('#submit').removeAttr('disabled');
                }

            });

        });


})