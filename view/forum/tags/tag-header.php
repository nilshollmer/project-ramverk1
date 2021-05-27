<?php

namespace Anax\View;

$tag = isset($tag) ? $tag : null;

?>

<?php if (!$tag) : ?>
    <p>Tag cannot be found.</p>
<?php
    return;
endif;
?>

<div class="tags-header">
    <h1><?= $tag->name ?></h1>
</div>
<p><?= $tag->description?></p>
<h2>Questions</h2>
<div class="tags-list">
