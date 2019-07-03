<?php
namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class OrdersEntity extends Entity
{
    private $id;

    private $clients_id;

    private $htPrice;

    private $created_at;

    private $token;

    //Getter and Setter

    public function getId() {
        return $this->id;
    }

    public function getClientsId() {
        return $this->clients_id;
    }
    public function setClientsId(int $id) {
        $this->clients_id = $id;
    }

    public function gethtPrice() {
        return $this->htPrice;
    }

    public function sethdPrice($price) {
        $this->htPrice = $price;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt($date) {
        $this->created_at = $date;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
    }
}
