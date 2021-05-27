<?php

namespace Anax\View;



// Create urls for navigation
$urlToCreate = url("question/create");
$urlToDelete = url("question/delete");

// var_dump($questions);
?>
<div class="questions-header">
    <h1>All Questions
    </h1>
    <a href="<?= $urlToCreate ?>" class="btn btn-large btn-blue float-right">
        Ask Question
    </a>
</div>

<div class="sort">
    Sort by:
    <a href=<?=url("questions?sortBy=newest")?>>Newest</a> |
    <a href=<?=url("questions?sortBy=oldest")?>>Oldest</a> |
    <a href=<?=url("questions?sortBy=views")?>>Views</a> |
    <a href=<?=url("questions?sortBy=votes")?>>Votes</a> |
    <a href=<?=url("questions?sortBy=answers")?>>Answers</a>
</div>

<div class="question-list">
