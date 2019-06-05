<?php
use App\Model \ {
    Post,
    Category
};

/**
 * fichier qui génère la vue pour l'url /article/[i:id]
 * 
 */
$id = (int)$params['id'];
$slug = $params['slug'];


$pdo = new PDO(
    "mysql:host=" .
        getenv('MYSQL_HOST') .
        ";dbname=" . getenv('MYSQL_DATABASE'),
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD')
);


$statement = $pdo->prepare("SELECT * FROM post WHERE id=?");
$statement->execute([$id]);
$statement->setFetchMode(PDO::FETCH_CLASS, Post::class);
$post = $statement->fetch();

$query = $pdo->prepare('
SELECT c.id, c.slug, c.name
FROM post_category pc
JOIN category c ON pc.category_id = c.id
WHERE pc.post_id = :id
');
$query->execute([':id' => $post->getId()]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
/** @var Category[] */
$categories = $query->fetchAll();
$title = "article : " . $post->getName();
?>


<div class="text-muted text-center pb-5 mb-5">
    <?= $post->getCreatedAt()->format('d/m/Y h:i') ?>
</div>
<?php foreach ($categories as $key => $category) :
    if ($key > 0) {
        echo ', ';
    }
    $category_url = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);
    ?><a href="<?= $category_url ?>"><?= $category->getName() ?></a><?php
                                                                endforeach ?>
<p class="mx-4 text-justify"><?= nl2br(htmlspecialchars($post->getContent())) ?></p>



</div>
</div>