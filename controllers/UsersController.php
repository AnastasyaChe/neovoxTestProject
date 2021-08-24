<?php


namespace app\controllers;

use app\base\Application;
use app\base\Session;
use app\models\repositories\UserRepository;

class UsersController extends Controller
{
    public function actionList()
    {     
         $notesOnPage = 5; 
       
        $users = [];    
        $users = (new UserRepository())->getLimit($this->page,  $notesOnPage, $this->search, 'date', 'DESC');      
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
    public function actionSearch() 
    {
        $notesOnPage = 5; 
        $users = []; 
            $searchText = Application::getInstance()->request->param('searchText');
            $users = (new UserRepository())->getLimit($this->page,  $notesOnPage, $searchText, 'date', 'DESC');   
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
    public function actionAdd() 
    {       
            if(Application::getInstance()->request->isPost()) {
                $userData = Application::getInstance()->request->post('user');
                $clearUserData = array_map(fn($el): string => trim($el), $userData);
                $clearUserData['ip'] = $_SERVER['REMOTE_ADDR'];
                $browse = $_SERVER['HTTP_USER_AGENT'];
                $browse = $this->user_min_browser($browse);
                $clearUserData['browse'] = $browse;
                $res = (new UserRepository())->insert($clearUserData);
                if($res) {
                    $msg = "Сообщение отправлено";
                }else {
                    $msg = "Ошибка";
                }
            $this->session->set('msg', $msg);
            header("Location: /users/list");
            exit;
            // return $this->actionList($msg);  
            }

    }

    public function actionSorting() 
    {
        $notesOnPage = 5;
           if(Application::getInstance()->request->isGet()) {
            
            $orderBy = Application::getInstance()->request->get('sorting');
            $rang = 'ASC';
                if(isset($_GET['rang'])) {
                $rang = Application::getInstance()->request->get('rang');
                }
            $users = (new UserRepository())->getLimit($this->page,  $notesOnPage, $this->search, $orderBy, $rang );
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
}



