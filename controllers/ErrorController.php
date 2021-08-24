<?php


namespace app\controllers;


class ErrorController extends Controller
{
    public function actionNotFound()
    {
        $this->useLayout = false;
        $this->render('not_found');
    }
}