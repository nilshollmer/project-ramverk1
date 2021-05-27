<?php

namespace Anax\View;

$user = isset($user) ? $user : null;

?>


<h2>Questions answered by <?= e($user->username) ?></h2>
