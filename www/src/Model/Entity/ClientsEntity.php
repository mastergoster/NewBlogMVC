<?php
namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class ClientsEntity extends Entity
{
    private $id;
    private $id_user;
    private $lastname;
    private $firstname;
    private $address;
    private $zipCode;
    private $city;
    private $country;
    private $phone;

    public function getId() {
        return $this->id;
    }
    public function getId_user() {
        return $this->id_user;
    }
    public function setId_user($id) {
        $this->id_user = $id;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname(string $lastname) {
        $this->lastname = $lastname;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname(string $firstname) {
        $this->firstname = $firstname;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress(string $address) {
        $this->address = $address;
    }

    public function getZipCode() {
        return $this->address;
    }

    public function setZipCode(string $zipCode) {
        $this->zipCode = $zipCode;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity(string $city) {
        $this->city = $city;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry(string $country) {
        $this->country = $country;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone(string $phone) {
        $this->phone = $phone;
    }
}
