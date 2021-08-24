<?php


namespace app\controllers;


use app\models\Product;
use app\models\User;

class UserController extends Controller
{
    public function actionLogin()
    {
        echo $this->render('autorisation', []);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
            if($user = User :: getByLogin($_POST['login'], $_POST['password'])) {
                echo "Autorized";
                session_start();
                $_SESSION['userId'] = $user['id'];
                
            } else {
                echo "Non autorized";
            }

        }
    }


    
    public function actionOut()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
        session_start();    
        unset($_SESSION['userId']);
        echo $this->render('autorisation', []);
        }
    }

    
}