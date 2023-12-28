<?php
function navbar(): void
{
    $id = $_SESSION["id"] ?? null;
    $first_name = $_SESSION["first-name"] ?? null;

    $header = "<header><div id='items'>";
    if ($id) $header .= "<span>Hi, <span id='user-name'>$first_name</span></span>";
    $header .= "<a href='/index.php'>Home</a>";
    if ($id) $header .= "<a href='../../login/logout.php'>Sign out</a>";
    $header .= "</div></header>";

    echo $header;
}
