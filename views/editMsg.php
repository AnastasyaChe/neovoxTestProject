<h2>Страница редактирования сообщения</h2>
<form action="/users/editMsg" method="post">
    <input type="hidden" name="user[id]" value="<?= $user->id; ?>">
    <textarea name="user[text]"><?= $user->text; ?></textarea><br />
    <?php foreach ($images as $image) : ?>
        <div class="img-item" id="<? $image->id; ?>">
            <?=
            $name = pathinfo($image->filename, PATHINFO_FILENAME);
            $ext = pathinfo($image->filename, PATHINFO_EXTENSION);
            ?>
            <img src="/uploads/<?= $name . '-thumb.' . $ext; ?>">
            <button class="delete" data-id="<?= $image->id; ?>">x</button>

        </div>
    <?php endforeach; ?>
    <input type="submit" name="send" value="Отправить">
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
</script>
<script>
    $(".delete").click(function() {
        let button = $(this);
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "/users/editMsg",
            dataType: 'json',
            data: {
                'id_img': button.data('id')
            },
            success: function(data) {
                if (data.success) {
                    alert('Изображение удалено');
                    $button.parent().remove();
                }

            }
        });
        return false;
    });
</script>