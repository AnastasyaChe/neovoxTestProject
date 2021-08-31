<h2>Вы можете изменить личные данные</h2>
<form action="/users/editUserInfo" method="post">
   
    <input type="hidden" name="user[id]" value="<?= $_SESSION['user']['id'] ?>">
    <input type="text" name="user[name]" value="<?= $_SESSION['user']['name'] ?>" placeholder="Имя"><br />
    <input type="text" name="user[email]" value="<?= $_SESSION['user']['email'] ?>" placeholder="email"><br />
    <input type="text" name="user[login]" value="<?= $_SESSION['user']['login'] ?>" placeholder="Логин"><br />
    <input type="password" name="user[password]" value="<?= $_SESSION['user']['password'] ?>" placeholder="Пароль"><br />

    <input type="submit" value="Принять изменения">
</form>