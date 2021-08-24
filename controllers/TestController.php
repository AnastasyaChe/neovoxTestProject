<?php

namespace app\controllers;

use app\base\Application;
use app\controllers\Controller;

class TestController extends Controller
{
    public function actionTest() 
    {
        if(Application::getInstance()->request->isPost()) {
            echo $this->render('list');
        }
        echo $this->render('test');
    }
}