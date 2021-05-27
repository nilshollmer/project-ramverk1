<?php

namespace Anax\View;


// Gather incoming variables and use default values if not set
$question    = isset($question) ? $question : null;
$user        = isset($user) ? $user : null;
$tags = isset($tags) ? $tags : null;


// var_dump($question);
?>

<?php if (!$question || !$user) : ?>
    <p>Question or user cant be found.</p>
<?php
    return;
endif;
?>

<div class="question-summary" onclick="window.location.href='<?= url('questions/' . $question->id)?>'">
    <div class="question-summary-values">
        <div class="num-votes">
            <?= $question->score ?> votes
        </div>
        <div class="num-answers">
            <?= $question->entries ?>  answers
        </div>
        <div class="num-views">
            <?= $question->views ?>  views
        </div>
    </div>
    <div class="summary">
        <h3><?= $question->title ?></h3>
        <div class="question-tags">
            <?php foreach($tags as $tag): ?>
                <a class="btn btn-small btn-light-blue" href=<?= url("tagged/") . "/". $tag->tag_name ?>><?= $tag->tag_name?></a>
            <?php endforeach; ?>
        </div>
        <div class="started">
            Asked <?= $question->created?>
            <a href=<?= url("users/") . "/". $user->username ?>><?= $user->username ?></a>
            <span class="user-reputation"><i class="fas fa-medal"></i><?= $user->reputation ?></span>
        </div>
    </div>
</div>
