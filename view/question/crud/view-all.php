<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("question/create");
$urlToDelete = url("question/delete");



?>
<div class="">
    <h1>Questions</h1>
    <div class="">
        <a href="<?= $urlToCreate ?>" class="btn btn-create">
            Create
        </a>
    </div>
</div>

<p>
    <a href="<?= $urlToCreate ?>">Create</a> |
    <a href="<?= $urlToDelete ?>">Delete</a>
</p>

<?php if (!$items) : ?>
    <p>There are no questions to show.</p>
<?php
    return;
endif;
?>

<div class="question-list">
<?php foreach ($items as $item) : ?>
    <div class="question-summary" onclick="window.location.href='<?= url('questions/'.$item->id)?>'">
        <div class="question-summary-values">
            <div class="num-votes">
                0 votes
            </div>
            <div class="num-answers">
                0 answers
            </div>
            <div class="num-views">
                0 views
            </div>
        </div>
        <div class="summary">
            <h3><a href="#"><?= $item->title ?></a></h3>
            <div class="tags">
                <a class="tag" href="#">javascript</a>
                <a class="tag" href="#">baseball</a>
                <a class="tag" href="#">godis</a>
                <a class="tag" href="#">php</a>
            </div>
            <div class="started">
                Asked 2021-01-07 22:02:22
                <a href="#"><?= $item->user ?></a>
                <span class="user-reputation">5</span>
            </div>
        </div>
    </div>
<?php endforeach; ?>

</div>
