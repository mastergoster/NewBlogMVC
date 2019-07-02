<?php
namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class Orders_ContentsEntity extends Entity
{
    private $id;

    private $id_beer;

    private $qty;

    private $token;

    //Getter and Setter

    public function getId() {
        return $this->id;
    }

    public function getIdBeer() {
        return $this->id_beer;
    }
    public function setIdBeer(int $id_beer) {
        $this->id_beer = $id_beer;
    }

    public function getQty() {
        return $this->qty;
    }

    public function setQty($qty) {
        $this->qty = $qty;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function getLineTotale() {
        return $this->price * $this->qty;
    }
}
