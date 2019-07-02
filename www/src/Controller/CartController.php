<?php

namespace App\Controller;

use \Core\Controller\Controller;

class CartController extends Controller
{
    public function __construct() {
        $this->loadModel('orders_Contents');
    }

    public function index() {
        $products = $this->orders_Contents->getLinesWithProducts();

        $priceTotalHT = 0;
        foreach($products as $key => $value) {
            $priceTotalHT += ($value->price * $value->getQty());
        }
        
        return $this->render('cart/index', [
            'products' => $products,
            'priceTotalHT' => $priceTotalHT
        ]);
    }

    /*
    * Ajax Method
    */
    public function getProductsInCart() {
        $_SESSION['cartNumber'] = $this->orders_Contents->getProductsInCart();
        echo $_SESSION['cartNumber'];
    }

    public function updateCart() {
        if(count($_POST) > 0) {
            $id = htmlspecialchars($_POST['id']);
            $qty = htmlspecialchars($_POST['qty']);
            
            if($this->orders_Contents->update($id, 'id', ['qty' => $qty])) {
                echo 'OK';
                die;
            }
            else {
                return false;
            }
        }
        header('location: /boutique/panier');
        exit();
    }

    public function delete() {
        if(count($_POST) > 0) {
            $id = htmlspecialchars($_POST['id']);
            if($this->orders_Contents->delete($id)) {
                echo 'OK';
                die;
            }
            else {
                return false;
            }
        }
    }
}

