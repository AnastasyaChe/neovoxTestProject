<!doctype html>
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

    .insert {
      border: gray solid 2px;
      margin: 10px;
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

    .user_img-list {
      display: flex;
      flex-wrap: wrap;
      flex-direction: row;
    }

    .form-row {
      margin-bottom: 15px;
    }

    .form-row label {
      display: block;
      color: #777;
      margin-bottom: 5px;
    }

    .form-row input[type="text"] {
      width: 100%;
      padding: 5px;
      box-sizing: border-box;
    }

    /* Стили для вывода превью */
    .img-item {
      display: inline-block;
      margin: 0 20px 20px 0;
      position: relative;
      user-select: none;
    }

    .img-item img {
      border: 1px solid #767676;
    }

    .img-item a {
      display: inline-block;
      background-color: white;
      clip-path: circle(50%);
      position: absolute;
      top: 0px;
      right: 0px;
      text-align: center;
      text-decoration: none;
      width: 20px;
      height: 20px;
      cursor: pointer;
    }

   </style>
  <?= $content ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
  </script>

  <script>
    $("#js-file").change(function() {
      if (window.FormData === undefined) {
        alert('В вашем браузере загрузка файлов не поддерживается');
      } else {
        var formData = new FormData();
        $.each($("#js-file")[0].files, function(key, input) {
          formData.append('file[]', input);
        });

        $.ajax({
          type: 'POST',
          url: '/users/upload',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          dataType: 'json',
          success: function(msg) {
            msg.forEach(function(row) {
              if (row.error == '') {
                $('#js-file-list').append(row.data);
              } else {
                alert(row.error);
              }
            });
            $("#js-file").val('');
          }
        });
      }
    });

    /* Удаление загруженной картинки */
    function remove_img(target) {
      $(target).parent().remove();
    }

    $('#preview').click(function() {
      let text = $("#text").val();
      $('#insertPlace').text(text);
    });
  </script>


</body>

</html>