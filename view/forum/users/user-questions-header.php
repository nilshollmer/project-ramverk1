<?php

namespace Anax\View;

$user = isset($user) ? $user : null;

?>

<h2>Questions asked by <?= e($user->username) ?></h2>
