<?php

namespace Anax\View;

$question = isset($question) ? $question : null;
$questionText = isset($questionText) ? $questionText : null;
$tags = isset($tags) ? $tags : null;
$user = isset($user) ? $user : null;
$comments = isset($comments) ? $comments : [];


?>
<?php if (!$question) : ?>
    <p>There are no question to show.</p>
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
                    <?= $questionText?>
                </p>
            </div>
            <div class="question-tags">
                <?php foreach($tags as $tag): ?>
                    <a class="btn btn-small btn-light-blue" href=<?= url("tagged") ."/". $tag->tag_name ?>><?= $tag->tag_name ?></a>
                <?php endforeach; ?>
            </div>
            <div class="question-user">
                <div class="user-info">
                    <small>Asked <?= $question->created?></small>
                    <div class="user-profile">
                        <div class="gravatar">
                            <img src="//www.gravatar.com/avatar/<?= md5($user->email)?>?r=pg&amp;s=60&amp;d=wavatar" alt="">
                        </div>
                        <div class="user-details">
                            <a href=<?= url("users/") . "/". $user->username ?>>
                                <?= $user->username?>
                            </a>
                            <span class="user-reputation"><i class="fas fa-medal"></i><?= $user->reputation?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="comments-list">
    <?php foreach($comments as $comment) : ?>
            <?php if ($comment->entry == null) : ?>
            <div class="comment">
                <div class="comment-text">
                    <?= $comment->text?>
                </div>-
                <a href="#">
                    <?= $comment->user?>
                </a>
                <span class="comment-date"><?= date("M d G:i", strtotime($comment->created))?></span>
            </div>
            <?php endif;?>
    <?php endforeach; ?>
    </div>
</div>
