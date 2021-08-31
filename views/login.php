
<h2>Пожалуйста, авторизуйтесь</h2>
<form action="/users/login" method="post">
    <input type="text" name="login" required placeholder="Логин"><br/>
    <input type="password" name="password" required placeholder="Пароль"><br/>
    <input type="submit" value="Войти">
</form>

<p><?=$_SESSION['msg3'];
    unset($_SESSION['msg3'])?></p>


