<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());
$question = isset($question) ? $question : null;
$entries = isset($entries) ? $entries : null;


?>
<?php if (!$question) : ?>
    <p>There are no questions to show.</p>
<?php
    return;
endif;
?>
<div class="question">
    <div class="question-header">
        <h1>
            <?= $question->title?>
        </h1>
        <p><span class="light-grey">Asked</span> <?= $question->created ?> <span class="light-grey">Viewed</span> <?= $question->views?></p>
    </div>
    <div class="question-main">
        <div class="question-votes">
            <i class="fas fa-caret-up fa-3x"></i>
            <p class="votes">0</p>
            <i class="fas fa-caret-down fa-3x"></i>
        </div>
        <div class="question-body">
            <div class="question-text">
                <p>
                    <?= $question->text?>
                </p>
            </div>
            <div class="question-tags">
                <a class="tag" href="#">hej</a>
                <a class="tag" href="#">på</a>
                <a class="tag" href="#">dig</a>
            </div>
            <div class="user-info">
                <small>Asked <?= $question->created?></small>
                <div class="user-profile">
                    <div class="gravatar">
                        <img src="//www.gravatar.com/avatar/<?= md5($user->email)?>?r=pg&amp;s=60&amp;d=wavatar" alt="">
                    </div>
                    <div class="user-details">
                        <a href="#">
                            <?= $user->username?>
                        </a>
                        <span class="user-reputation"><i class="fas fa-medal"></i><?= $user->reputation?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!$entries) : ?>
    <p>There are no entries to show.</p>
<?php
    return;
endif;
?>
<h2>Answers</h2>
<?php foreach ($entries as $item) : ?>
    <div class="question-summary">
        <div class="question-values">
            <i class="fas fa-caret-up fa-3x"></i>
            <p class="votes">0</p>
            <i class="fas fa-caret-down fa-3x"></i>
        </div>
        <div class="summary">
            <p><?= $item->text ?></p>

                <div class="user-info">
                    <small>Answered <?= $item->created?></small>
                    <div class="user-profile">
                        <div class="gravatar">
                            <img src="//www.gravatar.com/avatar/<?= md5($user->email)?>?r=pg&amp;s=60&amp;d=wavatar" alt="">
                        </div>
                        <div class="user-details">
                            <a href="#">
                                <?= $user->username?>
                            </a>
                            <span class="user-reputation"><i class="fas fa-medal"></i><?= $user->reputation?></span>
                        </div>
                    </div>

            </div>
        </div>
    </div>
<?php endforeach; ?>
