<?php
/**
 * Supply the basis for the navbar as an array.
 */


return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",

    // Here comes the menu items
    "items" => [
        [
            "text" => "About",
            "url" => "about",
            "title" => "About this site",
        ],
        [
            "text" => "Questions",
            "url" => "questions",
            "title" => "Questions",
        ],
        [
            "text" => "Tags",
            "url" => "tags",
            "title" => "Tags",
        ],
        [
            "text" => "Users",
            "url" => "users",
            "title" => "Users",
        ],
        [
            "text" => "Login",
            "url" => "user/login",
            "title" => "Login",
        ],
        [
            "text" => "Sign up",
            "url" => "user/create",
            "title" => "Sign up",
        ],
    ],
];
