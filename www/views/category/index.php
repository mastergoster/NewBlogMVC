<?php
use App\Model\Category;

$paginatedQuery = new App\PaginatedQuery(
    "SELECT count(id) FROM category",
    "SELECT * FROM category 
    ORDER BY id",
    Category::class,
    $router->url('categories'),
    10
);
$categories = $paginatedQuery->getItems();
$title = "Catégories";

echo "<ul>";
foreach ($categories as $category) {
    $url = $router->url('category', ['id' => $category->getId(), "slug" => $category->getSlug()]);
    echo "<li><a href=\"{$url}\">{$category->getName()}</a></li>";
}
?>
</ul>
<?php
echo $paginatedQuery->getNavHtml();
