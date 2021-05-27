<?php

namespace Anax\View;

$entries = isset($entries) ? $entries : null;
$user = isset($user) ? $user : null;

?>
<?php if (!$entries) : ?>
    <p>There are no entries to show.</p>
<?php
    return;
endif;
?>
<h2>Answers</h2>
<?php foreach ($entries as $item) : ?>
    <div class="question-summary">
        <div class="question-summary-values">
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
                            <a href=<?= url("users/") . "/". $user->username ?>>
                                <?= $user->username?>
                            </a>
                            <span class="user-reputation"><i class="fas fa-medal"></i><?= $user->reputation?></span>
                        </div>
                    </div>

            </div>
        </div>
    </div>
<?php endforeach; ?>
