<?php

namespace Anax\View;

$classes[] = "article";
if (isset($class)) {
    $classes[] = $class;
}


?><div <?= classList($classes) ?>>
<h1>We love to grow</h1>
    <div class="button-bar">
        <a class="btn btn-large btn-blue" href=<?= url("user/login")?>>login</a>
        <a class="btn btn-large btn-green" href=<?= url("user/create")?>>signup</a>
    </div>
</div>
