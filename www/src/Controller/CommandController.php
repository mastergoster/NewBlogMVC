<?php

namespace App\Controller;

use \Core\Controller\Controller;

class CommandController extends Controller
{
    public function __construct() {
        $this->loadModel('orders_Contents');
        $this->loadModel('clients');
        $this->loadModel('orders');
    }

    public function validation() {
        if(!$_SESSION['user']) {
            header('location: /connexion');
            exit();
        }
        $products = $this->orders_Contents->getLinesWithProducts();

        if(!$products) {
            header('location: /boutique/panier');
            exit();
        }

        $priceTotalHT = 0;
        foreach($products as $key => $value) {
            $priceTotalHT += ($value->price * $value->getQty());
        }

        if(count($_POST) > 0) {
            $requiredField = ['firstname', 'lastname', 'address', 'zipCode', 'city', 'country', 'phone'];
            foreach($requiredField as $value) {
                if(!$_POST[$value]) {
                    $_SESSION['error'] = "Merci de bien remplir la totalité des champs du formulaire ci dessous";
                    header('location: /command/validation');
                    exit();
                }
                $clients[$value] = htmlspecialchars($_POST[$value]);
            }
            $client = $this->clients->findOneByUserId($_SESSION['user']->getId());
            if($client) {
                $bool = $this->clients->update($client->getId(), 'id', $clients);
            }
            else {
                $clients['id_user'] = $_SESSION['user']->getId();
                $bool = $this->clients->create($clients);
                $client = $this->clients->findOneByUserId($_SESSION['user']->getId());
            }

            if($bool) {
                if(!$this->orders->findOneByToken($_SESSION['token'])) {
                    $date = new \DateTime('NOW');
                    $date = $date->format('Y-m-d H:i:s');
                    $fields = [
                        'id_user' => $client->getId(),
                        'Htprice' => $priceTotalHT,
                        'token' => $_SESSION['token'],
                        'created_at' => $date,
                        'statut' => 1
                    ];
                    if($this->orders->create($fields)) {
                        $_SESSION['success'] = 'Nous avons bien reçu votre commande<br/></br/>Merci !!';
                    }
                    else {
                        $_SESSION['error'] = 'Une erreur s\'est produite';
                    }

                    $this->render('/command/confirmation', [
                        'products' => $products,
                        'priceTotalHT' => $priceTotalHT
                    ]);

                    if($_SESSION['error']) {
                        unset($_SESSION['error']);
                    }
                    if($_SESSION['success']) {
                        unset($_SESSION['success']);
                    }
                    if($_SESSION['token']) {
                        unset($_SESSION['token']);
                    }
                    if($_SESSION['cartNumber']) {
                        unset($_SESSION['cartNumber']);
                    }

                    return true;
                }
                else {
                    $_SESSION['error'] = 'Cette commande a déjà été validée';
                    if($_SESSION['token']) {
                        unset($_SESSION['token']);
                    }  
                    if($_SESSION['cartNumber']) {
                        unset($_SESSION['cartNumber']);
                    }                  
                }
            }
        }

        $this->render('command/index', [
            'products' => $products,
            'priceTotalHT' => $priceTotalHT
        ]);

        if($_SESSION['error']) {
            unset($_SESSION['error']);
        }
    }
}

