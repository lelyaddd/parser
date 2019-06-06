<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="/frontend/js/installer.js"></script>

<style type="text/css">* {
        border-radius: 5px;
    }
    body, input {
        background-color: #f2f2f2
    }
    .install {
        background-color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -80%);
        /*border: 1px solid #000;*/
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        padding: 20px;
        width: 193px;
        padding: 40px;
    }
    input{
        margin-bottom: 5px;
        padding: 5px 10px;
    }

    input[type="submit"] {background-color: #41526b;
        color: #fff;
        border: none;
    }
    input[type="submit"]:hover {
        cursor: pointer;
    }
    .form input {
        width: -moz-available;

    }
</style>
    <form class="install" id="form">
    <div class="form">Сервер БД <br>
        <input type="text" id="host" required value="localhost">
    </div>
    <div class="form">Имя пользователя БД <br>
        <input type="text" id="user" required>
    </div>
    <div class="form">Пароль пользователя БД <br>
        <input type="password" id="password">
    </div>
    <div class="form">Имя БД<br>
        <input type="text" id="name" required>
    </div>
        <input type="submit" value="Установить">
    </form>
<div id="response"></div>