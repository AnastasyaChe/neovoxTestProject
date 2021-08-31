
<form action="/users/registration" method="post">

    <input type="text" name="user[name]" required placeholder="Имя"><br/>
    <input type="text" name="user[email]" required placeholder="email"><br/>
    <input type="text" name="user[login]" required placeholder="Логин"><br/>
    <input type="password" name="user[password]" required placeholder="Пароль"><br/>
    
    <input type="submit" value="Зарегистрироваться">

    
    <p><?=
                $_SESSION['msg1'];
                unset($_SESSION['msg1']);
                ?></p>
</form>