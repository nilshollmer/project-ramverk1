<?php


namespace Anax\View;

$user = isset($user) ? $user : null;
$questions = isset($questions) ? $questions : null;
?>


<?php if (!$user) : ?>
    <p>Users cannot be found.</p>
<?php
    return;
endif;
?>



<div class="user-info">
    <div class="user-profile">
        <div class="gravatar">
            <img src="//www.gravatar.com/avatar/<?= md5($user->email)?>?r=pg&amp;s=80&amp;d=wavatar" alt="">
        </div>
        <div class="user-details">
            <a href=<?= url("users/") . "/". $user->username ?>>
                <?= $user->username?>
            </a>
            <small><?= e($user->location)?></small>
            <small>Joined <?= date("M j, Y", strtotime($user->created))?></small>
            <small>Questions: <?= $questions ?></small>
            <span class="user-reputation"><i class="fas fa-medal"></i><?= $user->reputation?></span>
        </div>
    </div>
</div>
