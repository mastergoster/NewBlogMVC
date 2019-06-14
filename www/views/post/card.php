<article class="col-3 mb-4 d-flex align-items-stretch">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= $post->getName() ?></h5>
            <p class="card-text"><?= $post->getExcerpt(200) ?></p>
            <a  class="card-link" href="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) ?>">lire plus</a>

        </div>
        <ul class="list-group list-group-flush ">
            <?php foreach($post->getCategories() as $category): ?>
                <li class="list-group-item bg-light"><a href="<?= $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>"><?= $category->getName() ?></a></li>
            <?php endforeach ?>
        </ul>
        <div class="card-footer text-muted">
            <?= $post->getCreatedAt()->format('d/m/Y h:i')   ?>
        </div>
    </div>
</article>