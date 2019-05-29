<?php
/**
 * fichier qui génère la vue pour l'url /
 * 
 */
$title = "Home";
?>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum alias sapiente repudiandae expedita perferendis aliquid laboriosam, ducimus est adipisci vitae voluptate amet assumenda natus, veniam corrupti, enim praesentium? Sequi, maxime?</p>
<?php
for ($i = 0; $i < 20; $i++) {
    echo '<p></p>article ' . $i . '</p>';
}
