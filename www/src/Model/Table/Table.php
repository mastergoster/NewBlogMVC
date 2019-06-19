<?php
namespace App\Model\Table;

use \App\Controller\Database\DatabaseController;

class Table {
	 protected $db;

	 protected $table;

	 public function __construct(DatabaseController $db) {
	 	$this->db = $db;
	 	if(is_null($this->table)) {
	 		//App\Model\Table\ClassTable
	 		$parts = explode('\\', get_class($this));
	 		$class_name = end($parts);
	 		$this->table = strtolower(str_replace('Table', '', $class_name));
	 	}
	 }

	 public function query($statement, $attributes = null, $one = false, $class_name = null)
	 {
	 	if(is_null($class_name)) {
	 		$class_name = str_replace('Table', 'Entity', get_class($this));
	 	}

	 	if($attributes) {
	 		return $this->db->prepare(
	 				$statement,
	 				$attributes,
	 				$class_name,
	 				$one
	 			);
	 	}
	 	else {
	 		return $this->db->query(
	 				$statement,
	 				$class_name,
	 				$one
	 			);
	 	}
	 }
}