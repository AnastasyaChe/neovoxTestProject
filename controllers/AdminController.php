<?php


namespace app\controllers;


use app\base\Request;
use app\base\Session;
use app\models\Basket;
use app\models\repositories\ProductRepository;
use app\base\Application;

class AdminController extends Controller
{
    public function actionIndex()
    {
        $products = (new ProductRepository())->getAll();
        echo $this->render('admin/products/index.php', ['products' => $products]);
    }

    public function actionEdit() {
        $request = new Request();
        if($request->isPost()) {
            $product = $request->post('product');
            if (isset($product['id'])) {
                $id = $product['id'];
                unset($product['id']);
                (new ProductRepository())->update($product);
            }else {
                (new ProductRepository())->insert($product);
            }
            
        }
        
        $product = null;
        if (Application::getInstance()->request->get('id')){
            $product = (new ProductRepository())->getById($id);
        }
        echo $this->render('admin/products/edit.php', ['products' => $product]);   
    }
}