<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="script.js"></script>
        <title>Change URL - Сервис сокращения ссылок</title>
    </head>
    <body>
        <div class="block">
            <h1 class="block__title">Сервис сокращения ссылок</h1>
            <p class="block__info">
                Помогите клиентам быстро найти вашу страницу в интернете. Благодаря короткой ссылке клиентам не придётся видеть длинные url-адреса занимающие много места. Достаточно скопировать ссылку сервиса в буфер обмена.
            </p>
            <form action="#" class="block__form">
                <input class="form__element form__input" name="link" type="text" placeholder="Введите ссылку" required>
                <input class="link_hidden" type="hidden" name="link_hidden">
                <button class="form__element form__button">Сократить</button>
            </form>
            <div class="block__result">
                <p class="result__text">Ваша ссылка:</p>
                <a href="" class="result__link"></a>
            </div>
            <div class="block__error"></div>
        </div>
    </body>
</html>