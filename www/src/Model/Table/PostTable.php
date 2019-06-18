<?php
namespace App\Model\Table;

use App\Model\Entity\PostEntity;

class PostTable extends Table {

	public function allByLimit(int $limit, int $offset) {
		$posts = $this->query("{$this->query} LIMIT {$this->perPage}  OFFSET {$offset}");        
        
        $ids = array_map(function (PostEntity $post) {
            return $post->getId();
        }, $posts);
        
        
        $categories = (new CategoryTable($this->db))->allInId(implode(', ', $ids));
        
        $postById = [];
        foreach ($posts as $post) {
            $postById[$post->getId()] = $post;
        }
        foreach ($categories as $category) {
            $postById[$category->post_id]->setCategories($category);
        }

        return $postById;
	}
}