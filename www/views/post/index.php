<?php
/**
 * fichier qui génère la vue pour l'url /article/[i:id]
 * 
 */
$id = $params['id'];
$slug = $params['slug'];
$title = "article " . $slug;
?>

<p>article avec l'id <big><?= $id . '</big> et le slug <big>' . $slug ?></big></p>