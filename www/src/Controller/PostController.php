<?php
namespace App\Controller;

use \App\Model\Entity\PostEntity;
use \App\Model\Entity\CategoryEntity;

class PostController extends Controller{

    public function __construct()
    {
        $this->loadModel('post');
    }

    public function all()
    {
        $paginatedQuery = new PaginatedQueryController(
            $this->post,
            $this->generateUrl('home')
        );
        
        
        $title = 'Mon Super MEGA blog';
        $this->render('post/all', 
        [
            "title" => $title,
            "posts" => $postById,
            "paginate" => $paginatedQuery->getNavHtml()
        ]);
    }

    public function show(Array $params) {
        $id = (int)$params['id'];
        $slug = $params['slug'];


        $pdo = Connection::getPDO();

        $statement = $pdo->prepare("SELECT * FROM post WHERE id=?");
        $statement->execute([$id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        /** @var Post|false */
        $post = $statement->fetch();

        if (!$post) {
            throw new Exception('Aucun article ne correspond à cet ID');
        }

        if ($post->getSlug() !== $slug) {
            $url = $this->getRouter()->url(
                'post',
                [
                    'id' => $id,
                    'slug' => $post->getSlug()
                ]
            );
            http_response_code(301);
            header('Location: ' . $url);
            exit();
        }

        $query = $pdo->prepare("
                SELECT c.id, c.slug, c.name
                FROM post_category pc
                JOIN category c ON pc.category_id = c.id
                WHERE pc.post_id = :id
        ");
        $query->execute([':id' => $post->getId()]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Category::class);
        /** @var Category[] */
        $categories = $query->fetchAll();
        $title = "article : " . $post->getName();

        $this->render(
            "post/show", 
            [
                "title" => $title,
                "categories" => $categories,
                "post" => $post
            ]);
    }
}