$(document).ready(function () {
    $('.block__form').submit(function (e) {
        e.preventDefault();
        let error = false;
        let valUrl = $(this).find('.form__input').val();
        valUrl = valUrl.replace(/\/+$/, ''); // убираем лишние слеши в конце url
        $('.link_hidden').val(valUrl);

        const isGoodUrl = urlString => {
            // объект регулярного выражения
            const urlPattern = new RegExp('^(https?:\\/\\/)?'   + // проверка протокола
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'  + // проверка имени домена
            '((\\d{1,3}\\.){3}\\d{1,3}))'                       + // проверка ip адреса (версия 4, не 6)
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'                   + // проверка порта и пути
            '(\\?[;&a-z\\d%_.~+=-]*)?'                          + // проверка параметров запроса
            '(\\#[-a-z\\d_]*)?$','i');                            // проверка хэша
        
            // сама проверка
            return !!urlPattern.test(urlString);
        }

        if (valUrl == '') {
            error = true;
        } else if ((valUrl != '')) {
            if (!isGoodUrl(valUrl)) {
                error = true;
                $('.block__result').hide();
                $('.block__error').show();
                $('.block__error').text('Введите корректную ссылку');
            } else if (isGoodUrl(valUrl)) {
                $('.block__error').hide();
            }
        }
        
        if (!error) {
            $.ajax({
                url: 'process.php', 
                data: $(this).serialize(),
                type: 'GET',
                success: function (e) {
                    e = JSON.parse(e);
                    if (e.data == 'success') {
                        $('.block__result').show();
                        $('.result__link').attr('href', e.result);
                        $('.result__link').text(e.result);
                    } else if ((e.data == 'error')) {
                        $('.block__result').hide();
                        $('.block__error').show();
                        $('.block__error').text('Упс, что-то пошло не так :( Попробуйте позже');
                    }
                },
            });
        }
    });
    
    $('.result__link').click(function (e) {
        e.preventDefault();
        window.open($('.link_hidden').val(), '_blank');
    });
});