    <div class="container">
        <h2>Гостевая книга</h2>
        <?php if (isset($_SESSION['user'])) : ?>
            <h3>Добрый день, <?= $_SESSION['user']['name']; ?> !</h3>
            <a href="/users/out">Выход</a>
            <a href="/users/editUserInfo"> Редактировать личные данные пользователя</a>
        <?php else : ?>
            <a href="/users/login">Авторизоваться</a>
            <a href="/users/registration">Зарегистрироваться</a>
        <?php endif; ?>

        <div>
            <form action="/users/search" method="post">
                <input type="text" name="searchText" placeholder="Поиск" />
                <button id="search" href="#">Search</button>
            </form>
        </div>
        <nav>


            <ul class="topmenu">
                <li>Сортируйте сообщения:</li>
                <li><a href="">По пользователям</a>
                    <ul class="submenu">
                        <li><a href="/users/sorting/1/?sorting=name&rang=ASC">По возрастанию</a></li>
                        <li><a href="/users/sorting/1/?sorting=name&rang=DESC">По убыванию</a></li>
                    </ul>
                </li>
                <li><a href="">По email</a>
                    <ul class="submenu">
                        <li><a href="/users/sorting/1/?sorting=email&rang=ASC">По возрастанию</a></li>
                        <li><a href="/users/sorting/1/?sorting=email&rang=DESC">По убыванию</a></li>
                    </ul>
                </li>
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
                        <span>Имя:</span><?= htmlspecialchars($item->name) ?>
                        <span>email:</span><?= htmlspecialchars($item->email) ?>
                        <span>Время:</span> <?= $item->date ?>
                        <?php if ($_SESSION['user']['id'] == $item->user_id && (!is_null($_SESSION['user']['id']))) : ?>
                            <a href="/users/editMsg/1/?id=<?= $item->id; ?>">Изменить</a>
                        <?php endif; ?>
                    </th>
                </tr>
                <tr>
                    <td colspan="3">
                        <?= nl2br(htmlspecialchars($item->text)) ?>
                        <div class="user_img-list">
                            <?php foreach ($item->images as $image) : ?>
                                <div class="reviews_item-img">
                                    <?php
                                    $name = pathinfo($image->filename, PATHINFO_FILENAME);
                                    $ext = pathinfo($image->filename, PATHINFO_EXTENSION);
                                    ?>
                                    <a href="/uploads/<?php echo $image->filename; ?>" target="_blank">
                                        <img src="/uploads/<?php echo $name . '-thumb.' . $ext; ?>">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
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
            <a id="preview" href="#">Предпросмотр сообщения</a>
            <div class="insert">
                <p id="insertPlace"></p>
                <div id="prev"></div>
            </div>
        </div>

        <div>
            <form action="/users/add" enctype="multipart/form-data" method="post">
                <input type="text" name="user[name]" value="<?= $_SESSION['user']['name'] ?? '' ?>" required placeholder="Имя" /><br />
                <input type="email" name="user[email]" value="<?= $_SESSION['user']['email'] ?? '' ?>" required placeholder="Email" /><br />
                <input type="url" name="user[homepage]" placeholder="Homepage" /><br />
                <textarea cols="30" rows="7" id="text" name="user[text]" placeholder="Введите сообщение" required></textarea><br />

                <div class="form-row"><label>Выберите файл.Он должен быть jpg, png, gif, txt:</label>
                    <div class="img-list" id="js-file-list"></div>
                    <input id="js-file" type="file" name="file[]" multiple accept=".jpg, .png, .gif .txt">
                </div>
                <input type="submit" name="send" value="Отправить">
            </form>

            <p><?=
                $_SESSION['msg'];
                unset($_SESSION['msg']);
                ?></p>
        </div>
    </div>
    </script>
    </script>
    </body>

    </html>