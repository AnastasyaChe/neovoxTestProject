<?php


namespace app\controllers;

use app\base\Application;
use app\models\repositories\ProductRepository;

class ProductController extends Controller
{
    public function actionIndex()
    {
        echo "catalog";
    }

    public function actionCard()
    {
        $this->useLayout = false;
        $id = Application::getInstance()->request->get('id');

        $model = (new ProductRepository())->getById($id);
        echo $this->render('product_card', ['model' => $model]);
    }
}