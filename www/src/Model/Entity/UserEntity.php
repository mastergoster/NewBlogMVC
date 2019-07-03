<?php
namespace App\Model\Entity;

use Core\Model\Entity;

use Core\Controller\Helpers\TextController;

class UserEntity extends Entity
{
    private $id;
    private $mail;
    private $password;
    private $is_active;

    public function getId() {
        return $this->id;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail(string $mail) {
        $this->mail = $mail;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword(string $password) {
        $password = password_hash(htmlspecialchars($password), PASSWORD_BCRYPT);
        $this->password = $password;
    }

    public function getIs_active() {
        return $this->is_active;
    }

    public function setIs_active($bool) {
        $this->is_active = $bool;
    }
}
