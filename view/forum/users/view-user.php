<?php

namespace Anax\View;

$user = isset($user) ? $user : null;
$editable = isset($editable) ? $editable : null;

?>

<div class="user-main">
    <div class="user-profile-picture">
        <img src="//www.gravatar.com/avatar/<?= md5($user->email)?>?r=pg&amp;s=200&amp;d=wavatar" alt="">
        <h4 class="user-reputation"><?= $user->reputation?> Reputation <i class="fas fa-medal"></i></h4>
        <?php if ($editable) : ?>
            <a href="https://sv.gravatar.com/" target="_blank">No picture?</a>
        <?php endif; ?> 
    </div>
    <div class="user-profile-info">
        <h1 class="user-name"><?= e($user->username)?>

        </h1>
        <h4 class="user-occupation"><?= e($user->occupation)?></h4>
        <span class="user-location"><?= e($user->location)?></span>
        <?php if ($editable) : ?>
            <p><a href=<?= url("user/update") . "/" . $user->id?>>Click here to edit</a></p>
        <?php endif; ?>
    </div>
    <div class="user-profile-stats">
    </div>
</div>
