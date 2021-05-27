<?php

namespace Anax\View;


// Gather incoming variables and use default values if not set
$tag = isset($tag) ? $tag : null;
$questions = isset($questions) ? $questions : null;

?>

<?php if (!$tag) : ?>
    <p>Tag cannot be found.</p>
<?php
    return;
endif;
?>

<div class="tag-summary" onclick="window.location.href='<?= url('tags/' . $tag->name)?>'">

    <a class="btn btn-small btn-light-blue" href=<?= url("tags") ."/". $tag->name ?>><?= $tag->name ?></a>
    <p><?= substr($tag->description, 0, 150) ?>...</p>
    <p><?= $questions ?> Questions</p>
</div>
