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



/**
 *      $paginatedQuery = new App\PaginatedQuery(queryCount, query, class, url,perpage = 12)
 *      $post = $paginatedQuery->getContent()
 *      
 *      *** special ***
 *      querycount
 *      query
 *      class
 * 
 *      *** comun *** 
 *      perpage
 * 
 * 
 */
$nbpost = $pdo->query("SELECT count(category_id) FROM post_category WHERE category_id = {$category->getId()}")->fetch()[0];
$perPage = 12;
$nbPage = ceil($nbpost / $perPage);

if ((int)$_GET["page"] > $nbPage) {
    throw new Exception('pas de pages');
}

if (isset($_GET["page"])) {
    $currentpage = (int)$_GET["page"];
} else {
    $currentpage = 1;
}
$offset = ($currentpage - 1) * $perPage;


$statement = $pdo->query("
                    SELECT p.*
                    FROM post p
                    JOIN post_category pc ON pc.post_id = p.id
                    WHERE pc.category_id = {$category->getId()}
                    ORDER BY created_at DESC
                    LIMIT {$perPage} 
                    OFFSET {$offset}");

$statement->setFetchMode(PDO::FETCH_CLASS, Post::class);
/**@var Post[]|false */
$posts = $statement->fetchAll();

/**
 * 
 * fin refacto
 */


?>


<section class="row">
    <?php /** @var Post::class $post */
    foreach ($posts as $post) {
        require dirname(__dir__) . '/post/card.php';
    }
    ?>
</section>

<?php

/**
 * 
 * 
 * $paginatedQuery->getNavHTML();  ----> html
 * 
 * $paginatedQuery->getNav();   ->>   [1=>url, 2=>url]
 * 
 */

?>
<nav class="Page navigation">
    <ul class="pagination justify-content-center">

        <?php
        $uri = $router->url("category", ["id" => $category->getId(), "slug" => $category->getSlug()]);
        for ($i = 1; $i <= $nbPage; $i++) :
            $class = $currentpage == $i ? " active" : "";
            $url = $i == 1 ? $uri : $uri . "?page=" . $i; ?>
            <li class="page-item<?= $class ?>"><a class="page-link" href="<?= $url ?>"><?= $i ?></a></li>
        <?php endfor; ?>
    </ul>
</nav>