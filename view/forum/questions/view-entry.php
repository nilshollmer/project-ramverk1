<?php

namespace Anax\View;

$entry = isset($entry) ? $entry : null;
$entryText = isset($entryText) ? $entryText : null;
$user = isset($user) ? $user : null;
$comments = isset($comments) ? $comments : [];

?>

<?php if (!$entry) : ?>
    <p>There are no entry to show.</p>
<?php
    return;
endif;
?>
<div class="answer">
    <div class="answer-main">
        <div class="answer-votes">
            <i class="fas fa-caret-up fa-3x"></i>
            <p class="votes">0</p>
            <i class="fas fa-caret-down fa-3x"></i>
        </div>
        <div class="answer-body">
            <p><?= $entryText ?></p>
            <div class="answer-user">
                <div class="user-info">
                    <small>Answered <?= $entry->created?></small>
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
            <?php if ($comment->entry == $entry->id) : ?>
            <div class="comment">
                <div class="comment-text">
                    <?= $comment->text?>
                </div>-
                <a href=<?= url("users/") . "/". $comment->user ?>>
                    <?= $comment->user?>
                </a>
                <span class="comment-date"><?= date("M d G:i", strtotime($comment->created))?></span>
            </div>
            <?php endif;?>
    <?php endforeach; ?>
    </div>
</div>
