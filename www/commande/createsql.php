<?php
require_once '/var/www/vendor/autoload.php';

$pdo = new PDO('mysql:host=blog.mysql;dbname=blog', 'userblog', 'blogpwd');

//creation tables
echo "[";
$etape = $pdo->exec("CREATE TABLE post(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            content TEXT(650000) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id)
        )");
echo "||";
$etape = $pdo->exec("CREATE TABLE category(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )");
echo "||";
$etape = $pdo->exec("CREATE TABLE `user` (
    `id` int(11) NOT NULL,
    `mail` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `is_active` boolean NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
echo "||";
$etape = $pdo->exec("CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipCode` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
echo "||";
$pdo->exec("CREATE TABLE post_category(
            post_id INT UNSIGNED NOT NULL,
            category_id INT UNSIGNED NOT NULL,
            PRIMARY KEY(post_id, category_id),
            CONSTRAINT fk_post
                FOREIGN KEY(post_id)
                REFERENCES post(id)
                ON DELETE CASCADE
                ON UPDATE RESTRICT,
            CONSTRAINT fk_category
                FOREIGN KEY(category_id)
                REFERENCES category(id)
                ON DELETE CASCADE
                ON UPDATE RESTRICT
        )");
echo "||";
$pdo->exec("CREATE TABLE `beer` (
    `id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `img` text NOT NULL,
    `content` longtext NOT NULL,
    `price` float NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
echo "||";
$pdo->exec("CREATE TABLE `orders_contents` (
  `id` int(11) NOT NULL,
  `id_beer` int(11) NOT NULL,
  `qty` float NOT NULL,
  `token` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
echo "||";
$pdo->exec("CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `token` varchar(10) NOT NULL,
  `Htprice` float NOT NULL,
  `created_at` DATETIME NOT NULL,
  `statut`int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
echo "||";
$pdo->exec("CREATE TABLE `statut` (
  `id` int(11) NOT NULL,
  `statut` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
echo "||";

//vidage table
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE user');
$pdo->exec('TRUNCATE TABLE clients');
$pdo->exec('TRUNCATE TABLE orders');
$pdo->exec('TRUNCATE TABLE orders_content');
$pdo->exec('TRUNCATE TABLE statut');
$pdo->exec('TRUNCATE TABLE beer');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
echo "||||||||||||";
$faker = Faker\Factory::create('fr_FR');
echo "||";
$posts = [];
$categories = [];
echo "||";
for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO post SET
        name='{$faker->sentence()}',
        slug='{$faker->slug}',
        created_at ='{$faker->date} {$faker->time}',
        content='{$faker->paragraphs(rand(3, 15), true)}'");
    $posts[] = $pdo->lastInsertId();
    echo "|";
}

for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category SET
        name='{$faker->sentence(3, false)}',
        slug='{$faker->slug}'");
    $categories[] = $pdo->lastInsertId();
    echo "|";
}

foreach ($posts as $post) {
    $randomCategories = $faker->randomElements($categories, 2);
    foreach ($randomCategories as $category) {
        $pdo->exec("INSERT INTO post_category SET
                            post_id={$post},
                            category_id={$category}");
        echo "|";
    }
}

$password = password_hash('admin', PASSWORD_BCRYPT);
echo "||";

$pdo->exec("INSERT INTO user SET
        mail='admin@admin.fr',
        password='{$password}',
        is_active=1");
echo "||]";

$pdo->exec("INSERT INTO `orders` (`id`, `id_user`, `ids_product`, `priceTTC`) VALUES
(1, 1, 'a:3:{i:0;a:2:{s:3:\"qty\";s:2:\"10\";s:5:\"price\";s:4:\"1.91\";}i:1;a:2:{s:3:\"qty\";s:1:\"5\";s:5:\"price\";s:4:\"2.24\";}i:2;a:2:{s:3:\"qty\";s:2:\"10\";s:5:\"price\";s:4:\"1.74\";}}', 57.24),
(2, 1, 'a:3:{i:0;a:2:{s:3:\"qty\";s:2:\"10\";s:5:\"price\";s:4:\"1.91\";}i:1;a:2:{s:3:\"qty\";s:1:\"5\";s:5:\"price\";s:4:\"2.24\";}i:2;a:2:{s:3:\"qty\";s:2:\"10\";s:5:\"price\";s:4:\"1.74\";}}', 57.24),
(3, 1, 'a:3:{i:0;a:2:{s:3:\"qty\";s:2:\"10\";s:5:\"price\";s:4:\"1.91\";}i:1;a:2:{s:3:\"qty\";s:1:\"5\";s:5:\"price\";s:4:\"2.24\";}i:2;a:2:{s:3:\"qty\";s:2:\"10\";s:5:\"price\";s:4:\"1.74\";}}', 57.24),
(4, 1, 'a:3:{i:0;a:2:{s:3:\"qty\";s:1:\"8\";s:5:\"price\";s:4:\"1.66\";}i:1;a:2:{s:3:\"qty\";s:1:\"2\";s:5:\"price\";s:4:\"2.24\";}i:2;a:2:{s:3:\"qty\";s:1:\"9\";s:5:\"price\";s:4:\"1.29\";}}', 35.244),
(5, 1, 'a:3:{i:0;a:2:{s:3:\"qty\";s:1:\"9\";s:5:\"price\";s:4:\"1.91\";}i:1;a:2:{s:3:\"qty\";s:2:\"11\";s:5:\"price\";s:4:\"2.24\";}i:2;a:2:{s:3:\"qty\";s:2:\"13\";s:5:\"price\";s:4:\"2.24\";}}', 85.14),
(6, 1, 'a:3:{i:0;a:2:{s:3:\"qty\";s:2:\"14\";s:5:\"price\";s:4:\"1.91\";}i:1;a:2:{s:3:\"qty\";s:2:\"16\";s:5:\"price\";s:4:\"1.29\";}i:2;a:2:{s:3:\"qty\";s:2:\"25\";s:5:\"price\";s:4:\"1.74\";}}', 109.056),
(7, 1, 'a:3:{i:4;a:2:{s:3:\"qty\";s:2:\"15\";s:5:\"price\";s:4:\"2.08\";}i:5;a:2:{s:3:\"qty\";s:2:\"15\";s:5:\"price\";s:4:\"2.24\";}i:8;a:2:{s:3:\"qty\";s:1:\"7\";s:5:\"price\";s:4:\"1.74\";}}', 92.376)");

$pdo->exec("INSERT INTO `beer` (`id`, `title`, `img`, `content`, `price`) VALUES
(1, 'La Chouffe', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/la-chouffe-blonde-d-ardenne_opt.png?h=500&rev=899257661', 'Bière dorée légèrement trouble à mousse dense, avec un parfum épicé aux notes d’agrumes et de coriandre qui ressortent également au goût.', 1.91),
(2, 'Duvel', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/duvel_opt.png?h=500&rev=899257661', 'Robe jaune pâle, légèrement trouble, avec une mousse blanche incroyablement riche. L’arôme associe le citron jaune, le citron vert et les épices. La saveur incorpore des agrumes frais, le sucre de l’alcool et une note épicée due au houblon qui tire sur le poivre. En dépit de son taux d’alcool, c’est une bière fraîche qui se déguste facilement. ', 1.66),
(3, 'Duvel Tripel Hop', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/duvel-tripel-hop-citra.png?h=500&rev=39990364', 'Une variété supplémentaire de houblon est ajoutée à cette Duvel traditionnelle. Le HBC 291 lui procure un caractère légèrement plus épicé et poivré. Cette bière présente un fort taux d’alcool mais reste très facile à déguster grâce à ses arômes d’agrumes frais et acides, entre autres.', 2.24),
(4, 'Tremens', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/blond/delirium_tremens_2.png?h=500&rev=204392068', 'Bière dorée, claire à la mousse blanche pleine. Bière belge classique fortement gazéifiée et alcoolisée à la levure fruitée, arrière-goût doux.', 2.08),
(5, 'Nocturnum', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/delirium_nocturnum.png?h=500&rev=1038477262', 'Une bière rouge foncée brassée selon la tradition belge: à la fois forte et accessible. Des saveurs de fruits secs, de caramel et chocolat. Légèrement sucrée avec une touche épicée (réglisse et coriandre). La finale en bouche est chaude et agréable.', 2.24),
(6, 'Cuvée des Trolls', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/cuvee_des_trolls_2.png?h=500&rev=923839745', 'Bière brumeuse jaune paille à la mousse blanche consistante. Full body aux arômes fruités d’agrumes et de fruits jaunes. Grande douceur et petite touche acide rafraîchissante, levure. ', 1.29),
(7, 'Chimay Rouge', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/chimay---rood_v2.png?h=500&rev=420719671', 'Bière brune à la robe cuivrée avec une mousse durable, délicate et généreuse. Elle présente des arômes fruités de banane. D’autres parfums comme le caramel sucré, le pain frais, le pain grillé et même une touche d’amande sont aussi présents. Les mêmes arômes sucrés se retrouvent au goût et conduisent à une fin de bouche douce et légèrement amère. ', 1.49),
(8, 'Chimay Bleue', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/chimay---blauw_v2.png?h=500&rev=420719671', 'La Chimay Blauw, aussi connue sous le nom de Grande Réserve, est une bière trappiste reconnue. Il s’agissait au départ d’une bière de Noël, mais elle est disponible toute l’année depuis 1954. Une bière puissante et chaleureuse aux arômes de caramel et de fruits secs.', 1.74),
(9, 'Chimay Triple', 'https://www.beerwulf.com/globalassets/catalog/beerwulf/beers/chimay---wit_v2.png?h=500&rev=420719671', 'Robe de couleur doré clair, légèrement trouble avec une belle mousse blanche qui fera saliver les amateurs. Le nez et la bouche sont chargés de fruits comme le raisin et de levure. Une amertume ronde se dégage en fin de bouche.', 1.57)");

$pdo->exec("INSERT INTO `statut` (`id`, `statut`) VALUES
(1, 'En attente de paiement'),
(2, 'En cours de préparation'),
(3, 'En cours de livraison'),
(4, 'Terminée')");