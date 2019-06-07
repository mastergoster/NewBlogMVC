<?php
use App\Model \ {
    Category,
    Post
};
use App\Connection;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();

$statement = $pdo->prepare("SELECT * FROM category WHERE id=?");
$statement->execute([$id]);
$statement->setFetchMode(PDO::FETCH_CLASS, Category::class);
/** @var Category|false */
$category = $statement->fetch();

if (!$category) {
    throw new Exception('Aucune categorie ne correspond à cet ID');
}

if ($category->getSlug() !== $slug) {
    $url = $router->url(
        'category',
        [
            'id' => $id,
            'slug' => $category->getSlug()
        ]
    );
    http_response_code(301);
    header('Location: ' . $url);
    exit();
}

$title = 'categorie : ' . $category->getName();

$uri = $router->url("category", ["id" => $category->getId(), "slug" => $category->getSlug()]);
$paginatedQuery = new App\PaginatedQuery(
    "SELECT count(category_id) FROM post_category WHERE category_id = {$category->getId()}",
    "SELECT p.*
    FROM post p
    JOIN post_category pc ON pc.post_id = p.id
    WHERE pc.category_id = {$category->getId()}
    ORDER BY created_at DESC",
    Post::class,
    $uri
);
$posts = $paginatedQuery->getItems();
?>


<section class="row">
    <?php /** @var Post::class $post */
    foreach ($posts as $post) {
        require dirname(__dir__) . '/post/card.php';
    }
    ?>
</section>

<?php
echo $paginatedQuery->getNavHtml();
