<?php
namespace App\Model\Table;

use Core\Model\Table;

class ClientsTable extends Table
{
    public function findOneByUserId($id) {
        return $this->query("SELECT * FROM $this->table WHERE id_user = ?", [$id], true);
    }
}
