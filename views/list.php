<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML  4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Posts</title>

</head>

<body>
    <style>
        * {
            box-sizing: border-box;
        }

        .container {
            width: 80%;
            margin: 2% 2, 5%;
        }

        .list {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;

        }

        table {

            text-align: left;
        }

        td {
            padding: 2px 4px;
            border-bottom: 1px solid gainsboro;
        }

        th {
            padding-top: 20px;
        }

        span {
            color: gray;
        }

        li a {
            margin-right: 5px;
            text-decoration: none;
        }

        li {
            list-style-type: none;
            /* Убираем маркеры */
        }

        a.active {
            text-decoration: underline;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav a {
            display: block;
            text-decoration: none;
            outline: none;
            transition: .4s ease-in-out;
        }

        .topmenu {
            backface-visibility: hidden;
            background: rgba(255, 255, 255, .8);
        }

        .topmenu>li {
            display: inline-block;
            position: relative;
        }

        .topmenu>li>a {
            height: 70px;
            line-height: 70px;
            padding: 0 30px;
            color: #003559;

        }

        .down:after {
            margin-left: 8px;
        }

        .topmenu li a:hover {
            color: #E6855F;
        }

        .submenu {
            background: white;
            position: absolute;
            left: 0;
            top: 50px;
            visibility: hidden;
            opacity: 0;
            z-index: 5;
            width: 150px;
            transform: perspective(600px) rotateX(-90deg);
            transform-origin: 0% 0%;
            transition: .6s ease-in-out;
        }

        .topmenu>li:hover .submenu {
            visibility: visible;
            opacity: 1;
            transform: perspective(600px) rotateX(0deg);
        }

        .submenu li a {
            color: #7f7f7f;
            font-size: 13px;
            line-height: 20px;
            padding: 0 25px;
            font-family: 'Kurale', serif;
        }
    </style>

    <div class="container">
        <h2>Гостевая книга</h2>
        <div>
            <form action="/users/search" method="post">
                <input type="text" name="searchText" placeholder="Поиск" />
                <button id="search" href="#">Search</button>
            </form>
        </div>
        <nav>
            <h3>Сортируйте сообщения:</h3>

            <ul class="topmenu">
                <li><a href="">По пользователям</a>
                <ul class="submenu">
                        <li><a href="/users/sorting/1/?sorting=name&rang=ASC">По возрастанию</a></li>
                        <li><a href="/users/sorting/1/?sorting=name&rang=DESC">По убыванию</a></li>
                    </ul></li>
                <li><a href="">По email</a>
                <ul class="submenu">
                        <li><a href="/users/sorting/1/?sorting=email&rang=ASC">По возрастанию</a></li>
                        <li><a href="/users/sorting/1/?sorting=email&rang=DESC">По убыванию</a></li>
                    </ul></li>
                <li><a href="" class="down">По дате</a>
                    <ul class="submenu">
                        <li><a href="/users/sorting/1/?sorting=date&rang=ASC">По возрастанию</a></li>
                        <li><a href="/users/sorting/1/?sorting=date&rang=DESC">По убыванию</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <table>
            <?php if (empty($users)) : ?>
                <p>Не найдено записей</p>
            <?php endif; ?>
            <?php foreach ($users as $item) : ?>
                <tr>
                    <th>
                        <span>Имя:</span><?= htmlspecialchars($item->name) ?> <span>email:</span><?= htmlspecialchars($item->email) ?> <span>Время:</span> <?= $item->date ?>
                    </th>
                </tr>
                <tr>
                    <td colspan="3">
                        <?= nl2br(htmlspecialchars($item->text)) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div>
            <ul class="list">
                <?php for ($i = 1; $i <= $pagesCount; $i++) : ?>
                    <?php if ($currentPage == $i) :
                        $class = ' class="active"';
                    else :
                        $class = "";
                    endif; ?>

                    <li><a href="<?= $url . $i . "/" . $param . $searchText ?>" <?= $class ?>> <?= $i ?> </a></li>

                <?php endfor; ?>
            </ul>
        </div>
        <div>
            <form action="/users/add" method="post">
                <input type="text" name="user[name]" required placeholder="Имя" /><br />
                <input type="email" name="user[email]" required placeholder="Email" /><br />
                <input type="url" name="user[homepage]" placeholder="Homepage" /><br />
                <textarea cols="30" rows="7" name="user[text]" placeholder="Введите сообщение" required></textarea><br />
                <input type="submit" name="sub" value="Отправить">
            </form>
            <p><?=
                $_SESSION['msg'];
                unset($_SESSION['msg']);
                ?></p>
        </div>
    </div>
</body>

</html>