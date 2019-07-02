<?php
namespace App\Model\Table;

use Core\Model\Table;
use App\Model\Entity\PostEntity;

class OrdersTable extends Table
{
    public function findOneByToken($token) {
        return $this->query("SELECT * FROM $this->table WHERE token = ?", [$token], true);
    }
}
