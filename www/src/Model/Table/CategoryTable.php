<?php
namespace App\Model\Table;


class CategoryTable extends Table {
	
	public function allInId(string $ids) {
		return $this->query("SELECT c.*, pc.post_id
                FROM post_category pc 
                LEFT JOIN category c on pc.category_id = c.id
                WHERE post_id IN (".$ids.")");
	}
}