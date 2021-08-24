<?php


namespace app\controllers;


use app\base\Request;
use app\base\Session;
use app\models\Basket;
use app\models\repositories\ProductRepository;

class BasketController extends Controller
{
    public function actionIndex()
    {
        $basket = (new Basket())->getItems();
        echo $this->render('basket', ['basket' => $basket]);
    }

    public function actionAdd()
    {
        $request = new Request();
        if($request->isPost()) {
            $productId = $request->post('product_id');
            $productQty = $request->post('qty');
            (new Basket())->add($productId, $productQty);
        }
        echo json_encode(['status' => 'success', 'message' => 'товар успешно добавлен в корзину']);
    }
}