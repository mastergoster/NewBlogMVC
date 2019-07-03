<?php
namespace App\Model\Table;

use Core\Model\Table;

class Orders_ContentsTable extends Table
{
    public function getLinesWithProducts() {
        return $this->query("SELECT *, lineTable.id as lineID 
            FROM $this->table
            JOIN beer ON beer.id = $this->table.id_beer
            JOIN $this->table as lineTable ON lineTable.id = $this->table.id
            WHERE $this->table.token = ?", [$_SESSION['token']]);
    }

    public function getLine($params) {
        return $this->query("SELECT * FROM $this->table
            WHERE id_beer = ?
            AND token = ?", $params, true);
    }

    public function getProductsInCart() {
        return $this->query("SELECT COUNT(id) as nbProduct 
            FROM $this->table
            WHERE token = ?", [$_SESSION['token']], true)->nbProduct;
    }
}
