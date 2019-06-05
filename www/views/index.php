<?php
use App\Model\Post;
use App\Helpers\Text;


$pdo = new PDO(
    "mysql:host=" .
        getenv('MYSQL_HOST') .
        ";dbname=" . getenv('MYSQL_DATABASE'),
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD')
);



$nbpost = $pdo->query('SELECT count(id) FROM post')->fetch()[0];
$perPage = 12;
$nbPage = ceil($nbpost / $perPage);

if ((int)$_GET["page"] > $nbPage) {
    header('location: /');
    exit();
}

if (isset($_GET["page"])) {
    $currentpage = (int)$_GET["page"];
} else {
    $currentpage = 1;
}
$offset = ($currentpage - 1) * $perPage;

$statement = $pdo->query("SELECT * FROM post 
                    ORDER BY id 
                    LIMIT {$perPage} 
                    OFFSET {$offset}");

$statement->setFetchMode(PDO::FETCH_CLASS, Post::class);

$posts =   $statement->fetchAll();



$title = 'Mon Super MEGA blog';
?>

<?php if (null !== $message) : ?>
    <div class="alert-message">
        <?= $message ?>
    </div>
<?php endif ?>
<section class="row">
    <?php /** @var Post::class $post */
    foreach ($posts as $post) :
        //var_dump($post);
        //die();
        ?>
        <article class="col-3 mb-4 d-flex align-items-stretch">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $post->getName() ?></h5>
                    <p class="card-text"><?= Text::excerpt($post->getContent(), 200) ?></p>

                </div>
                <a href="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) ?>" class="text-center pb-2">lire plus</a>
                <div class="card-footer text-muted">
                    <?= $post->getCreatedAt()->format('d/m/Y h:i')   ?>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<nav class="Page navigation">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $nbPage; $i++) : ?>
            <?php $class = $currentpage == $i ? " active" : ""; ?>
            <?php $uri = $i == 1 ? "" : "?page=" . $i; ?>
            <li class="page-item<?= $class ?>"><a class="page-link" href="/<?= $uri ?>"><?= $i ?></a></li>
        <?php endfor ?>
    </ul>
</nav>