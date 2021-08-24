<?php


namespace app\controllers;


use app\base\Request;
use app\models\repositories\OrdersRepository;
use app\base\Session;

class OrdersController extends Controller
{
    public $session;
    public function __construct()
    {
        $this->session = new Session();
    }

    public function actionIndex()
    {

        $basket = [];
        if ($this->session->empty('basket')) {
            $productIds = array_filter(
                array_keys($items = $this->session->get('basket')),
                function ($element) {
                    return is_int($element);
                }
            );
            $products = (new OrdersRepository())->getByIds($productIds);
            foreach ($products as $product) {

                $basket[] = [
                    'product' => $product,
                    'qty' => $items[$product['id']]
                ];
            }
        }
        echo $this->render('product_card', ['basket' => $basket]);
    }
    public function actionChangeQty()
    {
        $request = new Request();
        $items = $this->session->get('basket');
        if ($request->isPost()) {
            $newData = $request->post('quantity');

            foreach ($newData as $key => $val) {
                if ($val == 0) {
                    unset($items[$key]);
                } else {
                    $items[$key] = $val;
                }
            }
        }
    }
}
