<?php


namespace app\controllers;

use app\base\Application;
use app\base\Session;
use app\models\Image;
use app\models\repositories\ImageRepository;
use app\models\repositories\RegistrationRepository;
use app\models\repositories\UserRepository;

class UsersController extends Controller
{
    public function actionList()
    {
        $notesOnPage = 5;
        $images = [];
        $users = [];
        $users = (new UserRepository())->getLimit($this->page,  $notesOnPage, $this->search, 'date', 'DESC');
        foreach ($users as $user) {
            $images = (new ImageRepository())->getByUserId($user->id);
            $user->images = $images;
        }
        $countOfItems =  (new UserRepository())->getCountOfItems();
        $countOfItems = +$countOfItems[0]->countItems;
        $pagesCount = ceil($countOfItems / $notesOnPage);
        $url = "/users/list/";
        echo $this->render('list', [
            'users' => $users,
            'pagesCount' => $pagesCount,
            'url' => $url,
            'currentPage' => $this->page,
            'param' => null,

        ]);
    }
    /**
     * actionSearch
     *
     * @return array
     */
    public function actionSearch()
    {
        $notesOnPage = 5;
        $images = [];
        $users = [];
        $searchText = Application::getInstance()->request->param('searchText');
        $users = (new UserRepository())->getLimit($this->page,  $notesOnPage, $searchText, 'date', 'DESC');
        foreach ($users as $user) {
            $images = (new ImageRepository())->getByUserId($user->id);
            $user->images = $images;
        }
        $countOfItems =  (new UserRepository())->getCountOfSearch($searchText);
        $countOfItems = +$countOfItems[0]->countItems;
        $pagesCount = ceil($countOfItems / $notesOnPage);
        $url = "/users/search/";
        $param =  "?searchText=";

        echo $this->render('list', [
            'users' => $users,
            'pagesCount' => $pagesCount,
            'url' => $url,
            'currentPage' => $this->page,
            'searchText' => $searchText,
            'param' => $param
        ]);
    }
    /**
     * actionAdd
     *
     * @return array
     */
    public function actionAdd()
    {
        if (Application::getInstance()->request->isPost()) {
            // Временная директория.
            $tmp_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/tmp/';
            // Постоянная директория.
            $path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

            $userData = Application::getInstance()->request->post('user');
            $clearUserData = array_map(fn ($el): string => trim($el), $userData);
            $clearUserData['ip'] = $_SERVER['REMOTE_ADDR'];
            $browse = $_SERVER['HTTP_USER_AGENT'];
            $browse = $this->user_min_browser($browse);
            $clearUserData['browse'] = $browse;
            $clearUserData['user_id'] = $_SESSION['user']['id'];
            $lastInsertId = (new UserRepository())->insert($clearUserData);
            if (!empty($_POST['images'])) {
                foreach ($_POST['images'] as $row) {
                    $filename = preg_replace("/[^a-z0-9\.-]/i", '', $row);
                    (new ImageRepository())->insertImages($lastInsertId, $filename);
                    rename($tmp_path . $filename, $path . $filename);
                    // перенос превью
                    $file_name = pathinfo($filename, PATHINFO_FILENAME);
                    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $thumb = $file_name . '-thumb.' . $file_ext;
                    rename($tmp_path . $thumb, $path . $thumb);
                }
            }

            if ($lastInsertId) {
                $msg = "Сообщение отправлено";
            } else {
                $msg = "Ошибка";
            }
            $this->session->set('msg', $msg);
            header("Location: /users/list");
            exit;
        }
    }

    /**
     * actionSorting
     *
     * @return array
     */
    public function actionSorting()
    {
        $notesOnPage = 5;
        if (Application::getInstance()->request->isGet()) {

            $orderBy = Application::getInstance()->request->get('sorting');
            $rang = 'ASC';
            if (isset($_GET['rang'])) {
                $rang = Application::getInstance()->request->get('rang');
            }
            $users = (new UserRepository())->getLimit($this->page,  $notesOnPage, $this->search, $orderBy, $rang);
            $countOfItems =  (new UserRepository())->getCountOfItems();
            $countOfItems = +$countOfItems[0]->countItems;
            $pagesCount = ceil($countOfItems / $notesOnPage);
            $url = "/users/sorting/";
            $param =  "?sorting=" . $orderBy . "&" . "rang=" . $rang;
            echo $this->render('list', [
                'users' => $users,
                'pagesCount' => $pagesCount,
                'url' => $url,
                'currentPage' => $this->page,
                'param' => $param,

            ]);
        }
    }
    /**
     * actionUpload
     *
     * @return array
     */
    public function actionUpload()
    {
        // Локаль.
        setlocale(LC_ALL, 'ru_RU.utf8');
        date_default_timezone_set('Europe/Moscow');
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_language('uni');

        //ini_set('display_errors', 1); 

        // Название <input type="file">
        $input_name = 'file';

        if (!isset($_FILES[$input_name])) {
            exit;
        }

        // Разрешенные расширения файлов.
        $allow = array('jpg', 'png', 'gif', 'txt');

        // URL до временной директории.
        $url_path = '/uploads/tmp/';

        // Полный путь до временной директории.
        $tmp_path = $_SERVER['DOCUMENT_ROOT'] . $url_path;

        if (!is_dir($tmp_path)) {
            mkdir($tmp_path, 0777, true);
        }

        // Преобразуем массив $_FILES в удобный вид для перебора в foreach.
        $files = array();
        $diff = count($_FILES[$input_name]) - count($_FILES[$input_name], COUNT_RECURSIVE);
        if ($diff == 0) {
            $files = array($_FILES[$input_name]);
        } else {
            foreach ($_FILES[$input_name] as $k => $l) {
                foreach ($l as $i => $v) {
                    $files[$i][$k] = $v;
                }
            }
        }

        $response = array();
        foreach ($files as $file) {
            $error = $data  = '';

            // Проверим на ошибки загрузки.
            $ext = mb_strtolower(mb_substr(mb_strrchr(@$file['name'], '.'), 1));
            if (!empty($file['error']) || empty($file['tmp_name']) || $file['tmp_name'] == 'none') {
                $error = 'Не удалось загрузить файл.';
            } elseif (empty($file['name']) || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Не удалось загрузить файл.';
            } elseif (empty($ext) || !in_array($ext, $allow)) {
                $error = 'Недопустимый 1 тип файла';
            } elseif ($file['type'] == 'text/plain' && strrpos($file['name'], '.txt') === strlen($file['name']) - strlen('.txt')) {
                $loadedFile = $this->uploadfile($file['name'], $tmp_path, $file['tmp_name']);
                if (!$loadedFile) {

                    $error = 'file is not loaded';
                }

                $data = '
                    <div class="img-item">
                    <p>' . $file['name'] . '</p>
                    <input id="textLoaded" type="hidden" id="loadedText" name="loadedText[] "value="' . $file['name'] . '.' . $ext . '">
                    </div>';
            } else {
                $info = @getimagesize($file['tmp_name']);
                if (empty($info[0]) || empty($info[1]) || !in_array($info[2], array(1, 2, 3))) {
                    $error = 'Недопустимый тип файла';
                } else {
                    // Перемещаем файл в директорию с новым именем.
                    $name  = time() . '-' . mt_rand(1, 9999999999);
                    $src   = $tmp_path . $name . '.' . $ext;
                    $thumb = $tmp_path . $name . '-thumb.' . $ext;

                    if (move_uploaded_file($file['tmp_name'], $src)) {
                        // Создание миниатюры.
                        switch ($info[2]) {
                            case 1:
                                $im = imageCreateFromGif($src);
                                imageSaveAlpha($im, true);
                                break;
                            case 2:
                                $im = imageCreateFromJpeg($src);
                                break;
                            case 3:
                                $im = imageCreateFromPng($src);
                                imageSaveAlpha($im, true);
                                break;
                        }

                        $width  = $info[0];
                        $height = $info[1];

                        // Высота превью 100px, ширина рассчитывается автоматически.
                        $h = 240;
                        $w = ($h > $height) ? $width : ceil($h / ($height / $width));
                        $tw = ceil($h / ($height / $width));
                        $th = ceil($w / ($width / $height));

                        $new_im = imageCreateTrueColor($w, $h);
                        if ($info[2] == 1 || $info[2] == 3) {
                            imagealphablending($new_im, true);
                            imageSaveAlpha($new_im, true);
                            $transparent = imagecolorallocatealpha($new_im, 0, 0, 0, 127);
                            imagefill($new_im, 0, 0, $transparent);
                            imagecolortransparent($new_im, $transparent);
                        }

                        if ($w >= $width && $h >= $height) {
                            $xy = array(ceil(($w - $width) / 2), ceil(($h - $height) / 2), $width, $height);
                        } elseif ($w >= $width) {
                            $xy = array(ceil(($w - $tw) / 2), 0, ceil($h / ($height / $width)), $h);
                        } elseif ($h >= $height) {
                            $xy = array(0, ceil(($h - $th) / 2), $w, ceil($w / ($width / $height)));
                        } elseif ($tw < $w) {
                            $xy = array(ceil(($w - $tw) / 2), ceil(($h - $h) / 2), $tw, $h);
                        } else {
                            $xy = array(0, ceil(($h - $th) / 2), $w, $th);
                        }

                        imageCopyResampled($new_im, $im, $xy[0], $xy[1], 0, 0, $xy[2], $xy[3], $width, $height);

                        // Сохранение.
                        switch ($info[2]) {
                            case 1:
                                imageGif($new_im, $thumb);
                                break;
                            case 2:
                                imageJpeg($new_im, $thumb, 100);
                                break;
                            case 3:
                                imagePng($new_im, $thumb);
                                break;
                        }

                        imagedestroy($im);
                        imagedestroy($new_im);

                        // Вывод в форму: превью, кнопка для удаления и скрытое поле.
                        $data = '
				<div class="img-item">
					<img  src="' . $url_path . $name . '-thumb.' . $ext . '"  height="100">
					<a href="#" onclick="remove_img(this); return false;">x</a>
					<input id="imageLoaded" type="hidden" name="images[]" value="' . $name . '.' . $ext . '">
				</div>';
                    } else {
                        $error = 'Не удалось загрузить файл.';
                    }
                }
            }

            $response[] = array('error' => $error, 'data'  => $data);
        }

        // Ответ в JSON.
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }
    /**
     * actionLogin
     *
     * @return void
     */
    public function actionLogin()
    {
        if (Application::getInstance()->request->isPost()) {
            $login = htmlspecialchars(Application::getInstance()->request->post('login'));
            $password = htmlspecialchars(Application::getInstance()->request->post('password'));
            if ($user = (new RegistrationRepository())->getByLogin($login, $password)) {
                $this->session->set('user', (array)$user);

                header("Location: /users/list");
                exit;
            }
            $msg3 = "Non autorized";
            $this->session->set('msg3', $msg3);
            header("Location: /users/login");
            exit;
        }
        echo $this->render('login');
    }
    /**
     * actionOut
     *
     * @return void
     */
    public function actionOut()
    {
        $this->session->exists('user');
        unset($_SESSION['user']);

        header("Location: /users/list");
    }

    /**
     * actionRegistration
     *
     * @return void
     */
    public function actionRegistration()
    {
        if (Application::getInstance()->request->isPost()) {
            $user = Application::getInstance()->request->post('user');
            $clearUserData = array_map(fn ($el): string => trim($el), $user);

            $lastInsertId = (new RegistrationRepository())->insert($clearUserData);
            $clearUserData['id'] = $lastInsertId;
            if ($lastInsertId) {
                $this->session->set('user', $clearUserData);

                header("Location: /users/list");
                exit;
            }
            $msg1 = "Ошибка";
            $this->session->set('msg1', $msg1);
            header("Location: /users/registration");
            exit;
        }
        echo $this->render('registration');
    }
    /**
     * actionEditUserInfo
     *
     * @return void
     */
    public function actionEditUserInfo()
    {
        if (Application::getInstance()->request->isPost()) {
            $user = Application::getInstance()->request->post('user');
            $clearUserData = array_map(fn ($el): string => trim($el), $user);
            (new RegistrationRepository())->update($clearUserData);

            $this->session->set('user', $clearUserData);
            header("Location: /users/list");
            exit;
        }
        echo $this->render('editUserInfo');
    }
    /**
     * actionEditMsg
     *
     * @return void
     */
    public function actionEditMsg()
    {
        if (Application::getInstance()->request->isGet()) {
            (int) $userId = Application::getInstance()->request->get('id');
            $user = (new UserRepository())->getById($userId);
            $images = (new ImageRepository())->getByUserId($userId);

            echo $this->render('editMsg', ['user' => $user, 'images' => $images]);
            exit;
        }
        if (Application::getInstance()->request->isPost()) {
            $user = Application::getInstance()->request->post('user');
            (new UserRepository())->updateText($user);
        }
        if ($idImg = Application::getInstance()->request->post('id_img')) {
            (new ImageRepository())->deleteImage($idImg);
            header('Content-Type: application/json');
            echo json_encode(array('success' => true), JSON_UNESCAPED_UNICODE);
            exit;
        }
        header("Location: /users/list");
        exit;
    }
}
