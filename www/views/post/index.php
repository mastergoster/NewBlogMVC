<?php
use App\Model\Post;
use App\Connection;

$paginatedQuery = new App\PaginatedQuery(
    "SELECT count(id) FROM post",
    "SELECT * FROM post 
    ORDER BY id",
    Post::class,
    $router->url('home')
);

$posts = $paginatedQuery->getItems();

$title = 'Mon Super MEGA blog';
?>

<?php if (null !== $message) : ?>
    <div class="alert-message">
        <?= $message ?>
    </div>
<?php endif ?>
<section class="row">
    <?php /** @var Post::class $post */
    foreach ($posts as $post) {
        require 'card.php';
    }
    ?>
</section>
<?php
echo $paginatedQuery->getNavHtml();
